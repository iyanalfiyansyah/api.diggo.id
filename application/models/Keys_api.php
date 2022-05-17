<?php

class Keys_api extends CI_Model
{
    public function getKeys($id = null)
    {
        if ($id === null) {
            return $this->db->get('api_keys')->result_array();
        } else {
            return $this->db->get_where('api_keys', ['id' => $id])->result_array();
        }
    }
}
