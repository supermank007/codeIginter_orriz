<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Polls extends Members_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->ion_auth->logged_in() == false) {
            redirect(base_url());
        }
        $this->load->model('polls_model');
    }

    public function index()
    {
        $this->load->model('messenger_model');
        $this->load->model('notifications_model');
        $data = [];
        $data['me'] = $this->me;
        $data['notSeenCount'] = $this->notifications_model->getNotSeenCount();
        $data['messengerList'] = $this->messenger_model->getMessengerList();
        foreach ($data['messengerList'] as $user) {
            $user->member_url = parent::makeMemberUrl($user->id, $user->profile_url);
        }
        $data['my_polls'] = $this->my_polls();
        $res = $this->all();
        $data['new_polls'] = $res->new;
        $data['voted_polls'] = $res->voted;
        $data['my_polls'] = $res->my;

        $this->load->view('polls/index', $data);
    }

    private function get_score($val, $arr)
    {
        $count = 0;
        foreach ($arr as $item) {
            if ($item == $val) $count++;
        }
        return 100 * $count / count($arr);
    }

    public function all()
    {
        $results = $this->polls_model->get_approved_polls();

        $polls = [];
        $res = (object)[];
        $res->new = [];
        $res->voted = [];
        $res->my = [];
        foreach ($results as $row) {
            $option_texts = explode(';;;', $row['options']);
            $option_ids = explode(';;;', $row['option_ids']);
            $user_ids = $row['user_ids'] != null ? explode(';;;', $row['user_ids']) : [];
            $answers = $row['answers'] != null ? explode(';;;', $row['answers']) : [];

            $poll = [
                'id' => $row['id'],
                'question' => $row['question'],
                'created_at' => $row['created_at'],
                'author_id' => $row['author_id'],
                'options' => [],
                'submitted' => false,
                'votesCount' => count($answers)
            ];

            foreach ($option_ids as $index => $option_id) {
                $option = [];
                $option['id'] = $option_id;
                $option['text'] = $option_texts[$index];

                if (in_array($this->me->id, $user_ids)) {
                    $poll['submitted'] = true;
                    $option['score'] = $this->get_score($option_id, $answers);
                }
                $poll['options'][] = $option;
            }

            if (isset($poll['submitted']) && $poll['submitted']) {
                array_push($res->voted, $poll);
            } else {
                array_push($res->new, $poll);
            }
            if ($poll['author_id'] == $this->me->id) {
                array_push($res->my, $poll);
            }
        }
        return $res;
    }

    protected function my_polls()
    {
        $polls = $this->polls_model->get_user_polls($this->me->id);
        return $polls;
    }

    public function create_answer()
    {
        $this->form_validation->set_rules('poll_id', 'Poll', 'required');
        $this->form_validation->set_rules('answer', 'Answer', 'required');

        if ($this->form_validation->run()) {
            $data = [
                'poll_id' => $this->input->post('poll_id'),
                'user_id' => $this->me->id,
                'poll_option_id' => $this->input->post('answer'),
            ];

            $this->polls_model->create_answer($data);

            redirect(base_url('polls'));
        } else {
            $this->session->set_flashdata('message', (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message'))));
            redirect(base_url('polls'), 'refresh');
        }
    }

    public function create()
    {
        $this->form_validation->set_rules('question', 'Question', 'required');
        if ($this->form_validation->run()) {
            if (count($this->input->post('options')) >= 2) {
                $data = [
                    'question' => $this->input->post('question'),
                    'author_id' => $this->me->id,
                    'allow' => 0,
                ];

                $poll_id = $this->polls_model->create_poll($data, $this->input->post('options'));
                $this->session->set_flashdata(['result_message' => 'success']);
                redirect(base_url('polls'));
            } else {
                $this->session->set_flashdata(['myFlashMessage' => 'Create at least 2 options']);
                $this->session->set_flashdata(['result_message' => 'error']);
                redirect(base_url('polls'), 'refresh');
            }
        } else {
            $this->session->set_flashdata(['myFlashMessage' => validation_errors()]);
            $this->session->set_flashdata(['result_message' => 'error']);
            redirect(base_url('polls'), 'refresh');
        }
    }
}