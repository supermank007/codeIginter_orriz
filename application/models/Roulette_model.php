<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roulette_model extends CI_Model
{
//    public function __construct()
//    {
//        parent::__construct();
//        $this->load->database();
//    }

    public function startGame($userID, $money)
    {
        $this->db->insert('roulette_info', array('userID' => $userID, 'money' => $money));
    }

    public function getCurMoney($userID)
    {
        $this->db->select('money');
        $query = $this->db->get_where('roulette_info', ['userID' => $userID]);
        return $query->result_array()[0]['money'];
    }

    public function getTopRated()
    {
        $query = "SELECT R.userID, R.wins, R.losts, M.first_name, M.last_name FROM roulette_info as R LEFT JOIN members as M ON M.id = R.userID ORDER BY (R.wins / (R.wins + R.losts)) DESC LIMIT 100";
        return $this->db->query($query)->result();
    }


    public function getCurPoints($userID)
    {
        $this->db->select(['points']);
        $query = $this->db->get_where('members', ['id' => $userID]);
        $points = $query->result_array()[0]['points'];
        return $points;
    }

    public function updatePoints($userID, $points)
    {
        return $this->db->update_batch('members', [0 => ['points' => $points, 'id' => $userID]], 'id');
    }

    public function updateMoney($userID, $curMoney, $type = null)
    {
        if ($type) {
            $is_win = null;
            if ($type == 'win') {
                $this->db->set('wins', 'wins+1', FALSE);
            } else {
                $this->db->set('losts', 'losts+1', FALSE);
            }
            $this->db->where('userID', $userID);
            $this->db->update('roulette_info');
        }
        return $this->db->update_batch('roulette_info', [0 => ['money' => $curMoney, 'userID' => $userID]], 'userID');
    }
}
