<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Games extends Members_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('roulette_model');
//        $this->load->helper('url');
//        $this->load->library(array('form_validation', 'session'));
    }

//    public function index()
//    {
//        redirect(base_url(''), 'auto');
//    }

    public function roulette()
    {
        $this->load->model('messenger_model');
        $this->load->model('notifications_model');
        $data = [];
        $data['notSeenCount'] = $this->notifications_model->getNotSeenCount();
        $data['messengerList'] = $this->messenger_model->getMessengerList();
        foreach ($data['messengerList'] as $user) {
            $user->member_url = parent::makeMemberUrl($user->id, $user->profile_url);
        }
        $data['me'] = $this->me;
        $data['money'] = $this->roulette_model->getCurMoney($this->session->userdata('user_id'));
        $data['points'] = $this->roulette_model->getCurPoints($this->session->userdata('user_id'));
        $data['topRated'] = $this->roulette_model->getTopRated();

        if ($data['money'] == null) {
            $data['money'] = 1000;
            $this->roulette_model->startGame($this->session->userdata('user_id'), $data['money']);
        }
        $this->load->view('roulette/roulette', $data);
    }

    public function roulette_update_data()
    {
        $points = intval($this->input->post('points'));
        $money = intval($this->input->post('money'));
        $type = $this->input->post('type');
        $isPointsUpdated = false;
        $isMoneyUpdated = false;
        if ($points) {
            if ($points < 0) {
                $points = 0;
            }
            $isPointsUpdated = $this->roulette_model->updatePoints($this->me->id, $points);
        }
        if ($money) {
            if ($money < 0) {
                $money = 0;
            }
            $isMoneyUpdated = $this->roulette_model->updateMoney($this->me->id, $money, $type);
        }
        if (($points && !$isPointsUpdated) || ($money && !$isMoneyUpdated)) {
            echo json_encode(['result' => 'error']);
            exit;
        }
        echo json_encode(['result' => 'ok']);
        exit;
    }
}
