<?php

class Cashiers_api extends CI_Model
{
    public function getCashiers($id = null)
    {
        if ($id === null) {
            return $this->db->get('cashier_sales')->result_array();
        } else {
            $this->db->where('id', $id);
            return $this->db->get('cashier_sales')->result_array();
        }
    }

    public function postCashierSales($data)
    {
        return $this->db->insert('cashier_sales', $data);
        //return $this->db->insert('cashier_sales', ['owner_id' => $data['owner_id'], 'outlet_id' => $data['outlet_id'], 'employee_id' => $data['employee_id'], 'invoice_number' => $data['invoice_number']]);
    }

    public function postCashierCarts($data)
    {
        return $this->db->insert('cashier_carts', $data);
    }
}
