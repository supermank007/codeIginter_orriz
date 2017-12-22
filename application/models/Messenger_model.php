<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messenger_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }

    public function add_message($data)
    {
        $this->db->insert('messages', $data);
        return $this->db->insert_id();
    }

    public function get_messages($receiver_id)
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->query("SELECT * FROM messages WHERE sender_id = $user_id && receiver_id = $receiver_id || sender_id = $receiver_id && receiver_id = $user_id ORDER BY  `created_at` ASC ");
        return $query->result_array();
    }

//    public function getMessengerListWithFriends()
//    {
//        $id = $this->session->userdata('user_id');
//        $query = $this->db->query("SELECT F.status,M.id, M.profile_url, M.first_name, M.last_name, M.image, M.last_seen, MS.created_at as messageDate
//                            FROM members M, friends F, messages MS WHERE CASE
//                            WHEN F.friend_one = '$id'
//                            THEN F.friend_two = M.id
//                            WHEN F.friend_two= '$id'
//                            THEN F.friend_one= M.id
//                            END
//                            AND
//                            F.status='2'");
//        $friends = $query->result();
//
//        $query = $this->db->query("SELECT DISTINCT M.id, M.profile_url, M.first_name, M.last_name, M.image,M.last_seen, MS.created_at as messageDate
//                            FROM members M, messages MS WHERE (MS.sender_id = '$id' OR MS.receiver_id = '$id') AND M.id != '$id' ");
//        $messageUsers = $query->result();
//        $res = $friends;
//        foreach ($res as $r) {
//            if (!isset($r->messageDate)) {
//                $r->messageDate = 0;
//            }
//        }
//        foreach ($messageUsers as $user) {
//            $flag = false;
//            foreach ($friends as $friend) {
//                if ($user->id == $friend->id) {
//                    break;
//                } else {
//                    $flag = true;
//                }
//            }
//            if ($flag) {
//                array_push($res, $user);
//            }
//        }
//        usort($res, function ($a, $b) {
//            return strtotime($a->messageDate) - strtotime($b->messageDate);
//        });
//
//        return $res;
//    }

    public function getMessengerList($params = [])
    {
        $id = $this->session->userdata('user_id');
        $query = $this->db->query("SELECT created_at as messageDate, sender_id, receiver_id 
                            FROM messages WHERE sender_id = '$id' OR receiver_id = '$id' 
                            ORDER BY messageDate DESC ");
        $messages = $query->result();
        $res = [];
        $temp = [];

        for ($i = 0; $i < count($messages); $i++) {
            if ($messages[$i]->sender_id == $id) {
                $temp[$messages[$i]->receiver_id] = $messages[$i]->messageDate;
            } else {
                $temp[$messages[$i]->sender_id] = $messages[$i]->messageDate;
            }
        }
        $ids = [];
        foreach ($temp as $key => $value) {
            array_push($ids, $key);
        }

        if (isset($params['withFriends'])) {
            $query = $this->db->query("SELECT M.id 
                            FROM members M, friends F WHERE CASE
                            WHEN F.friend_one = '$id'
                            THEN F.friend_two = M.id
                            WHEN F.friend_two= '$id'
                            THEN F.friend_one= M.id
                            END
                            AND
                            F.status='2'");
            $friends = $query->result();
            foreach ($friends as $friend) {
                array_push($ids, $friend->id);
            }
            $ids = array_unique($ids);
        }

        if (count($ids)) {
            $this->db->select('id, profile_url, first_name, last_name, image, last_seen');
            $this->db->from('members');
            $this->db->where_in('id', $ids);

            $res = $this->db->get()->result();
            foreach ($res as $r) {
                for ($i = 0; $i < count($messages); $i++) {
                    if ($messages[$i]->receiver_id == $r->id || $messages[$i]->sender_id == $r->id) {
                        $r->messageDate = $messages[$i]->messageDate;
                        break;
                    }
                }
            }
            usort($res, function ($a, $b) {
                return strtotime($b->messageDate) - strtotime($a->messageDate);
            });
        }
        return $res;
    }
}
