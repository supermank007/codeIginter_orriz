<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Members_Controller
{


    public function __construct()
    {
        parent::__construct();

        if ($this->ion_auth->logged_in() == false) {
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->load->model('member_model');
        $this->load->model('photos_model');
        $this->load->model('messenger_model');
        $this->load->model('notifications_model');
        $this->load->helper('tree_helper');
        $this->load->helper('url');
        $id = $this->session->userdata('user_id');
    }

    public function index()
    {
        $username = $this->uri->segment('1');
        $seg2 = $this->uri->segment('2');

        if (empty($username)) {
            show_404($this->uri->uri_string);
        }

//        if ($this->uri->segment('2') != "") {
//            $user_id = '';
//            if (is_numeric($this->uri->segment('2'))) {
//                $user_id = intval($this->uri->segment('2'));
//            } else {
//                $user_id = $this->member_model->check_profile_url($this->uri->segment('2'));
//            }
//            if ($user_id != '') {
//                $this->data['users_data'] = $this->member_model->get_user_data($user_id);
//                $this->load->view('members/userprofile_', $this->data);
//            } else {
//                echo 'Invalid User';
//            }
//        } else {
//            echo 'Not Found';
//        }

        $user_id = $this->member_model->check_profile_url($username);
        if (!$user_id && is_numeric($username)) {
            if (!$this->member_model->check_member_id(intval($username))) {
                show_404($this->uri->uri_string);
            } else {
                $user_id = intval($username);
            }
        }
        if ($user_id) {
            $data = [];
            $data['notSeenCount'] = $this->notifications_model->getNotSeenCount();
            $data['messengerList'] = $this->messenger_model->getMessengerList();
            foreach ($data['messengerList'] as $user) {
                $user->member_url = parent::makeMemberUrl($user->id, $user->profile_url);
            }
            $data['users_data'] = $this->member_model->get_user_data($user_id);
            $data['users_data'][0]['member_url'] = parent::makeMemberUrl($data['users_data'][0]['id'], $data['users_data'][0]['profile_url']);
//            $data['users_data']['count_views'] = $this->member_model->count_of_views($user_id) + 1;
            //friends
            $interval = parent::intervalTime($data['users_data'][0]['last_seen']);
            $data['users_data'][0]['last_active'] = parent::intervalToString($interval);
            if ($data['users_data'][0]['is_hidden'] != NULL) {
                $interval = parent::intervalTime($data['users_data'][0]['is_hidden']);
                $data['users_data'][0]['is_hidden'] = parent::intervalToString($interval);
            }
            $users_friend_list = $this->member_model->friend_list($user_id);
            $data['friend_list'] = $this->member_model->friend_list($data['me']->id);

            $friend_request_sent = $this->member_model->friend_request_sent($user_id);
            $data['isFriendRequestSent'] = false;
            foreach ($friend_request_sent as $frs) {
                if ($frs->id == $user_id) {
                    $data['isFriendRequestSent'] = true;
                    break;
                }
            }

            $data['isFriend'] = false;

            if ($users_friend_list != null) {
                for ($i = 0; $i < count($users_friend_list); $i++) {
                    $users_friend_list[$i]['member_url'] = parent::makeMemberUrl($users_friend_list[$i]['id'], $users_friend_list[$i]['profile_url']);
                    if ($users_friend_list[$i]['id'] == $this->session->userdata('user_id')) {
                        $data['isFriend'] = true;
                    }
                }
                $data['users_friend_list'] = $users_friend_list;
            }

            $data['photos'] = $this->photos_model->getMemberPhotos($user_id, 'gallery');
            usort($data['photos'], function ($a, $b) {
                return strtotime($b->created_at) - strtotime($a->created_at);
            });
            $data['me'] = $this->member_model->get_user($this->session->userdata('user_id'))[0];
            $data['me']->member_url = parent::makeMemberUrl($data['me']->id, $data['me']->profile_url);
            $freeluv = parent::checkFreeLuv();
            if (is_int($freeluv)) {
                $data['me']->free_luv = $freeluv;
            } else {
                $data['me']->free_luv = 0;
            }

            if ($seg2 && $seg2 == 'gallery') {
                $membersIds = [];
                foreach ($data['photos'] as $photo) {
                    if (count($photo->likes)) {
                        foreach ($photo->likes as $like) {
                            array_push($membersIds, $like->member_id);
                        }
                    }
                    if (count($photo->comments)) {
                        foreach ($photo->comments as $comment) {
                            array_push($membersIds, $comment->member_id);
                        }
                        usort($photo->comments, function ($a, $b) {
                            return strtotime($a->created_at) - strtotime($b->created_at);
                        });
                    }
                }

                $photoId = $this->input->post('fakeFormPhotoId');
                $data['openPhotoId'] = $photoId ? $photoId : null;
                $membersIds = array_unique($membersIds);
                $members = $this->member_model->get_user_data_by_array($membersIds);
                $temp = [];
                foreach ($members as $member) {
                    $member->member_url = parent::makeMemberUrl($member->id, $member->profile_url);
                    $temp[$member->id] = $member;
                }
                $data['members'] = $temp;
                $data['user'] = $this->member_model->get_user($user_id)[0];
                $data['user']->member_url = parent::makeMemberUrl($data['user']->id, $data['user']->profile_url);
                $data['userGallery'] = true;
                $this->load->view('photos/gallery', $data);

            } else {
                if ($user_id != $this->session->userdata('user_id')) {
                    $this->member_model->add_visited_member($user_id);
                }
                $data['points'] = $this->me->points;
                $data['is_hidden'] = $this->me->is_hidden;
                $this->load->view('members/userprofile_', $data);
            }
        } else {
            show_404($this->uri->uri_string);
        }
    }

}