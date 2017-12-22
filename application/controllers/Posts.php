<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends Members_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->ion_auth->logged_in() == false) {
            redirect(base_url());
        }
        $this->load->model('member_model');
        $this->load->model('posts_model');
        $this->load->model('photos_model');

//        $id = $this->session->userdata('user_id');
//        $data = [
//            'last_activity_timestamp' => date('Y-m-d h:i:s', time()),
//            'is_login' => 1
//
//        ];
//
//        $this->member_model->update_members_profile($id, $data);
    }


    public function status_insert()
    {
        $id = $this->session->userdata('user_id');
        $this->form_validation->set_rules('status', 'Status', 'trim');
        $this->form_validation->set_rules('video', 'Video', 'trim');
        $video = $this->input->post('video');
        $text = $this->input->post('status');
        $image = $_FILES['image']['name'];
        $data = [
            'member_id' => $id,
            'privacy' => $this->input->post('privacy', True),
            'time' => gmdate("Y-m-d  H:i:s")
        ];
        $res = [];

        if ($image) {
            $extension = substr($image, strrpos($image, '.') + 1);
            $imagename = md5(uniqid() . time()) . "." . $extension;
            $config['upload_path'] = './public/images/photos/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '2048';
            $config['file_name'] = $imagename;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('image') == false) {
                $this->data['message'] = $this->upload->display_errors();
            } else {
                $im = $this->upload->data('file_name');
                $data['photos'] = $im;
            }
            $data ['show_in_gallery'] = 0;
        }

        if ($text) {
            $data ['status'] = $this->input->post('status', True);
        }

        if ($video) {
            $videoServices = ['youtube', 'dailymotion'];
            $html = false;
            foreach ($videoServices as $service) {
                $html = $this->isEmbeddableVideoURL($video, $service);
                if ($html) {
                    $data['video'] = $this->setIframeSize($html);
                    break;
                }
            }
            if (!$html) {
                $res['response'] = 'error';
                echo json_encode($res);
                exit;
            }
        }
        $insert_id = $this->posts_model->insert_post($data);
        $this->data['posts'] = $this->posts_model->get_one_post($insert_id);
        for ($i = 0; $i < count($this->data['posts']); $i++) {
            $this->data['posts'][$i]['time'] = parent::convertToUserTimezone($this->data['posts'][$i]['time']);
        }
        $this->data['userImage'] = $this->posts_model->get_user_image($id);
        $res['post'] = $this->load->view('members/posts', $this->data, true);
        $res['response'] = 'ok';
        echo json_encode($res);
        exit;
    }

    private function isEmbeddableVideoURL($url, $service)
    {
        $oembedURL = '';
        switch ($service) {
            case 'youtube':
                $oembedURL = 'www.youtube.com/oembed?url=' . urlencode($url) . '&format=json';
                break;
            case 'dailymotion':
                $url_array = explode('/', $url);
                $dmUrl = 'http://www.dailymotion.com/video/' . end($url_array);
                $oembedURL = 'http://www.dailymotion.com/services/oembed?url=' . urlencode($dmUrl) . '&format=json';
                break;
            default:
                return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $oembedURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output);
        if (!$data) {// Either 404 or 401 (Unauthorized)
            return false;
        }
        if (!$data->{'html'}) {// Embeddable video MUST have 'html' provided
            return false;
        }
        return $data->{'html'};
    }

    private function setIframeSize($html)
    {
        $xpath = new DOMXPath(@DOMDocument::loadHTML($html));
        return $xpath->evaluate("string(//iframe/@src)");
    }

    public function luv()
    {
        if (isset($_GET['type']) && isset($_GET['postid'])) {
            $type = $_GET['type'];
            $post_id = (int)$_GET['postid'];
            switch ($type) {
                case 'posts':
                    $this->posts_model->posts_like($this->session->userdata('user_id'), $post_id);
                    break;
            }
            redirect(base_url('dashboard'));
        }
    }

    public function like_post()
    {
        if (isset($_POST['post_id'])) {

// Find Like
            $post_id = (int)$_POST['post_id'];
            $model = $this->posts_model->find_like_on_post($this->session->userdata('user_id'), $post_id);
            if ($model == 0) {
                $this->posts_model->posts_like($this->session->userdata('user_id'), $post_id);
                echo "1";
            } else {
                $this->posts_model->remove_post_like($this->session->userdata('user_id'), $post_id);
                echo "0";
            }
        }
    }

    public function get_like()
    {
        if (isset($_POST['post_id'])) {
            $post_id = (int)$_POST['post_id'];
            $result = $this->posts_model->get_posts_like($post_id);
            echo $result[0]['likes'];
        }
    }

    public function privacy()
    {
        $this->form_validation->set_rules('privacy', 'Privacy', 'required|trim');
        if ($this->form_validation->run() == True) {
            $data = ['privacy' => $this->input->post('privacy')];
            $id = $this->session->userdata('user_id');
            $this->member_model->update_members_profile($id, $data);
            redirect(base_url('dashboard'), 'refresh');
        } else
            redirect(base_url('dashboard'), 'refresh');
    }

    public function add_comment()
    {
        $this->form_validation->set_rules('comment', 'Comment', 'required|trim');
        $this->form_validation->set_rules('post_id', 'Post ID', 'required|trim');
        if ($this->form_validation->run() == True) {
            $user_id = $this->session->userdata('user_id');
            $post_id = $this->input->post('post_id', True);
            $comment = $this->input->post('comment', True);
            if ($this->posts_model->posts_comment($user_id, $post_id, $comment) != false) {
                echo "success";
            }
        } else
            echo "Please enter your comment";
    }

    public function get_comments()
    {
        if (isset($_POST['post_id'])) {
            $post_id = (int)$_POST['post_id'];
            $this->data['comments'] = $this->posts_model->get_posts_comments($post_id);
            if ($this->data['comments'] != false) {
                for ($i = 0; $i < count($this->data['comments']); $i++) {
                    $this->data['comments'][$i]['timestamp'] = parent::convertToUserTimezone($this->data['comments'][$i]['timestamp']);
                }
                $this->view('members/comments', $this->data);
            } else echo "No comment found";
        }
    }

    public function ajex_load_posts()
    {
        $this->load->model('posts_model');
        $start = $_POST['start'];
        $records_per_page = $_POST['records_per_page'];
        $privacy = $_POST['privacy'];
        $friends = ($_POST['friends']);

        $this->data['userImage'] = $this->posts_model->get_user_image($this->session->userdata('user_id'));
        $this->data['privacy'] = $privacy;
        if ($privacy == 1) {
            $this->data['posts'] = $this->posts_model->select_rows_privacy1($start, $records_per_page, $friends);
            for ($i = 0; $i < count($this->data['posts']); $i++) {
                $this->data['posts'][$i]['member_url'] = parent::makeMemberUrl($this->data['posts'][$i]['member_id'], $this->data['posts'][$i]['profile_url']);
                $interval = parent::intervalTime($this->data['posts'][$i]['time']);
                $this->data['posts'][$i]['last'] = parent::intervalToString($interval);
                $this->data['posts'][$i]['time'] = parent::convertToUserTimezone($this->data['posts'][$i]['time']);
            }
            if (count($this->data['posts'])) {
                return $this->view('members/posts', $this->data, true);
            } else {
                exit('');
            }
        } elseif ($privacy == 2) {
            $friendDetails = $this->posts_model->select_friend_detail($start, $records_per_page, $this->session->userdata('user_id'));
            $friend_two = array();
            foreach ($friendDetails as $friends) {
                if ($friends['friend_two'] != $this->session->userdata('user_id')) {
                    $friend_two[] = $friends['friend_two'];
                } else {
                    $friend_two[] = $friends['friend_one'];
                }
            }
            if (!empty($friend_two)) {
                $this->data['posts'] = $this->posts_model->select_rows_privacy2($start, $records_per_page, $friend_two);
                for ($i = 0; $i < count($this->data['posts']); $i++) {
                    $this->data['posts'][$i]['member_url'] = parent::makeMemberUrl($this->data['posts'][$i]['member_id'], $this->data['posts'][$i]['profile_url']);
                    $interval = parent::intervalTime($this->data['posts'][$i]['time']);
                    $this->data['posts'][$i]['last'] = parent::intervalToString($interval);
                    $this->data['posts'][$i]['time'] = parent::convertToUserTimezone($this->data['posts'][$i]['time']);
                }
                if (count($this->data['posts'])) {
                    return $this->view('members/posts', $this->data, true);
                } else {
                    exit('');
                }
            }
        } elseif ($privacy == 3) {
            $this->data['posts'] = $this->posts_model->select_rows_privacy3($start, $records_per_page, $friends);
            for ($i = 0; $i < count($this->data['posts']); $i++) {
                $this->data['posts'][$i]['member_url'] = parent::makeMemberUrl($this->data['posts'][$i]['member_id'], $this->data['posts'][$i]['profile_url']);
                $interval = parent::intervalTime($this->data['posts'][$i]['time']);
                $this->data['posts'][$i]['last'] = parent::intervalToString($interval);
                $this->data['posts'][$i]['time'] = parent::convertToUserTimezone($this->data['posts'][$i]['time']);
            }
            if (count($this->data['posts'])) {
                return $this->view('members/posts', $this->data, true);
            } else {
                exit('');
            }
        }

    }

    public function view($page = 'home', $data = null)
    {
        if (!file_exists(APPPATH . '/views/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            show_404($this->uri->uri_string);
        }
        $this->load->view($page, $data);
    }


}