<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications_model extends CI_Model
{
    public function getNotSeenCount()
    {
        $id = $this->session->userdata('user_id');
        $res = (object)[];

        $this->db->select('receiver_id')->from('messages')->where(['receiver_id' => $id, 'is_seen' => 0]);
        $res->messages = $this->db->count_all_results();

        $this->db->select('is_seen')->from('friends')->where(['friend_two' => $id, 'is_seen' => 0, 'status' => 1]);
        $res->friends = $this->db->count_all_results();

        $this->db->select('id')->from('luv')->where(['to_member_id' => $id, 'is_seen' => 0]);
        $res->luv = $this->db->count_all_results();

        return $res;
    }

    public function updateNotifications($type)
    {
        $id = $this->session->userdata('user_id');
        switch ($type) {
            case 'messages':
                $this->db->where('receiver_id', $id)->update('messages', ['is_seen' => 1]);
                break;
            case 'friends':
                $this->db->where(['friend_two' => $id, 'status' => 1])->update('friends', ['is_seen' => 1]);
                break;
            case 'luv':
                $this->db->where('to_member_id', $id)->update('luv', ['is_seen' => 1]);
        }
    }
}