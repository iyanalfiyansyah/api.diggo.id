<?php

class Products_api extends CI_Model
{
    public function getProducts($id = null)
    {
        if ($id === null) {
            return $this->db->get('product_infos')->result_array();
        } else {
            $this->db->where('id', $id);
            return $this->db->get('product_infos')->result_array();
        }
    }
}
