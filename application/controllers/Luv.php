<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Luv extends Members_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->ion_auth->logged_in() == false) {
            redirect(base_url());
        }
        $this->load->model('member_model');
        $this->load->model('photos_model');
        $this->load->model('luv_model');
        date_default_timezone_set('UTC');
    }

    public function index()
    {
        $this->load->model('messenger_model');
        $this->load->model('notifications_model');
        $this->notifications_model->updateNotifications('luv');
        $data = [];
        $data['me'] = $this->me;
        $freeluv = parent::checkFreeLuv();
        if (is_int($freeluv)) {
            $data['me']->free_luv = $freeluv;
        } else {
            $data['me']->free_luv = 0;
            $data['me']->till_free_luv = $freeluv;
        }

        $luvs = $this->luv_model->getAllLuv();
        foreach ($luvs as $key => $luv) {
            $luv->member_url = parent::makeMemberUrl($key, $luv->profile_url);
            $now = new DateTime(gmdate("Y-m-d  H:i:s"));
            foreach ($luv->luvs as $l) {
                $last = new DateTime($l->sent_at);
                $interval = $now->diff($last);
                $l->last = parent::intervalToString($interval);
            }
        }
        $data['points'] = $data['me']->points;
        $data['is_hidden'] = $data['me']->is_hidden;
        $data['members'] = $luvs;
        $data['notSeenCount'] = $this->notifications_model->getNotSeenCount();
        $data['messengerList'] = $this->messenger_model->getMessengerList();
        foreach ($data['messengerList'] as $user) {
            $user->member_url = parent::makeMemberUrl($user->id, $user->profile_url);
        }
        $this->load->view('luv/luv', $data);
    }

    public function give_luv_ajax()
    {
        $myId = $this->session->userdata('user_id');
        $to_member_id = $this->input->post('to_member_id');
        $to = $this->member_model->get_email($to_member_id)[0]['email'];
        $data['members'] = $this->member_model->get_user($to_member_id)[0];
        $data['me'] = $this->member_model->get_user($myId)[0];
        $forPoints = $this->input->post('forPoints');
        $freeluv = parent::checkFreeLuv();
        if (is_int($freeluv)) {
            if ($this->luv_model->giveLuv($myId, $to_member_id)) {
                $this->luv_model->give_luv_points($myId, $to_member_id);
                if (!$this->member_model->isOnline($to_member_id)) {
                    $subject = "Luv In Orriz";
                    $body = $this->load->view('public/email/luvmail', $data, true);
                    parent::sendEmail($to, $subject, $body);
                }
                echo json_encode(['result' => 'ok']);
            } else {
                echo json_encode(['result' => 'error']);
            }
        } else if ($forPoints) {
            $points = intval($this->member_model->get_points($myId)[0]['points']);
            if ($points >= 5 && $this->member_model->debit_points($myId, 5)) {
                if ($this->luv_model->giveLuv($myId, $to_member_id, 0)) {
                    $this->luv_model->give_luv_points($myId, $to_member_id);
                    if (!$this->member_model->isOnline($to_member_id)) {
                        $subject = "Luv In Orriz";
                        $body = $this->load->view('public/email/luvmail', $data, true);
                        parent::sendEmail($to, $subject, $body);
                    }
                    echo json_encode(['result' => 'ok']);
                } else {
                    echo json_encode(['result' => 'error']);
                }
            } else {
                echo json_encode(['result' => 'error']);
            }
        } else {
            echo json_encode(['result' => 'error', 'message' => $freeluv]);
        }
    }

    public function not_show_ajax()
    {
        $member_id = $this->input->post('member_id');
        if ($member_id && $this->luv_model->notShow($member_id)) {
            echo json_encode(['result' => 'ok']);
        } else {
            echo json_encode(['result' => 'error']);
        }
    }

}