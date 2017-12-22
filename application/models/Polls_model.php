<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Polls_model extends CI_Model
{
    public $tname_poll = 'polls';
    public $tname_option = 'poll_options';
    public $tname_answer = 'poll_answers';

    public function create_poll($data, $options)
    {
        try {
            $result = $this->db->insert($this->tname_poll, $data);
        } catch (Exception $ex) {
            return FALSE;
        }
        $poll_id = $this->db->insert_id();
        if (!$poll_id) return FALSE;
        foreach ($options as $option) {
            if (trim($option) === '') return;
            $this->db->insert($this->tname_option, [
                'poll_id' => $poll_id,
                'text' => $option
            ]);
        }
        return $poll_id;
    }

    public function create_answer($data)
    {
        // create duplicate answer
        $this->db->where('poll_id', $data['poll_id']);
        $this->db->where('user_id', $data['user_id']);
        $this->db->select('COUNT(*)');
        $query = $this->db->get($this->tname_answer);
        $result = $query->row_array();
        if ($result['COUNT(*)'] > 0) return FALSE;
        try {
            $result = $this->db->insert($this->tname_answer, $data);
        } catch (Exception $ex) {
            return FALSE;
        }
        return $result;
    }

    public function get_all($user_id)
    {
        $sql = "SELECT A.id, A.question, A.allow, A.created_at, B.options" .
            " FROM $this->tname_poll AS A" .
            " LEFT JOIN " .
            "(SELECT poll_id, GROUP_CONCAT(text SEPARATOR ';;;') AS options FROM $this->tname_option GROUP BY poll_id) AS B" .
            " ON B.poll_id = A.id ORDER BY A.created_at DESC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if ($result) {
            foreach ($result as &$poll) {
                $poll['options'] = explode(';;;', $poll['options']);
            }
        }
        return $result;
    }

    public function get_approved_polls()
    {
        $sql = "SELECT A.id, A.question, A.created_at, A.author_id, B.options, B.option_ids, C.user_ids, C.answers" .
            " FROM $this->tname_poll AS A" .
            " LEFT JOIN " .
            "(SELECT poll_id, GROUP_CONCAT(id SEPARATOR ';;;') AS option_ids, GROUP_CONCAT(text SEPARATOR ';;;') AS options FROM $this->tname_option GROUP BY poll_id) AS B" .
            " ON B.poll_id = A.id" .
            " LEFT JOIN " .
            "(SELECT poll_id, GROUP_CONCAT(user_id SEPARATOR ';;;') AS user_ids, GROUP_CONCAT(poll_option_id SEPARATOR ';;;') AS answers FROM $this->tname_answer GROUP BY poll_id) AS C" .
            " ON C.poll_id = A.id" .
            " WHERE A.allow = 1 ORDER BY A.created_at DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_user_polls($user_id)
    {
        $sql = "SELECT A.question, A.created_at, A.id, B.options" .
            " FROM $this->tname_poll AS A" .
            " LEFT JOIN " .
            "(SELECT poll_id, GROUP_CONCAT(text SEPARATOR ';;;') AS options FROM $this->tname_option GROUP BY poll_id) AS B" .
            " ON B.poll_id = A.id" .
            " WHERE A.author_id = $user_id ORDER BY A.created_at DESC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $ids = [];
        if ($result) {
            foreach ($result as &$poll) {
                $poll['options'] = explode(';;;', $poll['options']);
                $poll['submitted'] = false;
                array_push($ids, $poll['id']);
            }
        }

        $res = $this->db->select('poll_id')
            ->from('poll_answers')
            ->where('user_id', $user_id)
            ->where_in('poll_id', $ids)
            ->get()
            ->result();

        foreach ($res as $submittedPostId) {
            foreach ($result as &$r) {
                if ($r['id'] == $submittedPostId->poll_id) {
                    $r['submitted'] = true;
                    break;
                }
            }
        }
        return $result;
    }

    public function approve_poll($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tname_poll, ['allow' => 1]);
    }
}