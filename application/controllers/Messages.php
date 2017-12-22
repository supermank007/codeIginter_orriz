<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Messages extends Members_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('messenger_model');
        $this->load->model('member_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $this->load->model('notifications_model');
        $this->notifications_model->updateNotifications('messages');
        $this->data['me'] = $this->me;
        $this->data['me']->member_url = parent::makeMemberUrl($this->data['me']->id, $this->data['me']->profile_url);
        $freeluv = parent::checkFreeLuv();
        if (is_int($freeluv)) {
            $this->data['me']->free_luv = $freeluv;
        } else {
            $this->data['me']->free_luv = 0;
        }
        $this->data['notSeenCount'] = $this->notifications_model->getNotSeenCount();
        $this->data['messengerListWithFriends'] = $this->messenger_model->getMessengerList(['withFriends' => true]);
        $this->load->view('messages/index', $this->data);
    }

    public function add_message()
    {
        $data = [
            'sender_id' => $this->me->id,
            'receiver_id' => $this->input->post('receiver_id'),
            'message' => $this->input->post('message'),
        ];
        if ($data['receiver_id'] != $data['sender_id'] && $this->messenger_model->add_message($data)) {
            parent::notifyNode(['type' => 'newMessage',
                'message' => $data['message'],
                'recieverId' => $data['receiver_id'],
                'senderId' => $data['sender_id']]);
            echo(json_encode(['result' => 'ok']));
            exit;
        } else {
            echo(json_encode(['result' => 'error']));
            exit;
        }
    }

    public function get_messages()
    {
        $receiver_id = $this->input->post('receiver_id');
        $res = [];
        $this->data['message'] = $this->messenger_model->get_messages($receiver_id);
        $res['message'] = $this->data['message'];
        $isOnline = $this->member_model->isOnline($receiver_id);
        $userR = $this->member_model->get_user_data($receiver_id);
        if(!empty($userR)) {
            $checkFriend = $this->member_model->isFriend($receiver_id);
            $friendStatus = !empty($checkFriend[0]['status']) ? $checkFriend[0]['status'] : 0;
            $userR = $userR[0];
            $res['user'] = array(
                'first_name' => $userR['first_name'],
                'last_name' => $userR['last_name'],
                'profile_url' => parent::makeMemberUrl($receiver_id, $userR['profile_url']),
                'isOnline' => $isOnline,
                'friendStatus' => $friendStatus,
            );

            if(!$isOnline) {
                $res['user']['last_seen'] = $userR['last_seen'];
            }
        }
        
        $res['result'] = 'ok';
        echo json_encode($res);
        exit;

    }

    public function update_notifications_ajax()
    {
        $this->load->model('notifications_model');
        $this->notifications_model->updateNotifications('messages');
    }
}