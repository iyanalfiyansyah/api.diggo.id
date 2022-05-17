<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Cashiers extends REST_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['carts_post']['limit'] = 500;
        $this->load->model('Cashiers_api');
    }

    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            $cashiers = $this->Cashiers_api->getCashiers();
        } else {
            $cashiers = $this->Cashiers_api->getCashiers($id);
        }

        if ($cashiers) {
            $this->response([
                'status' => true,
                'data' => $cashiers
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => FALSE,
                'message' => 'ID could not be found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        $data = [
            'owner_id' => $this->post('owner_id'),
            'outlet_id' => $this->post('outlet_id'),
            'employee_id' => $this->post('employee_id'),
            'invoice_number' => $this->post('invoice_number'),
            'sub_total' => $this->post('sub_total'),
            'discount_persen' => $this->post('discount_persen'),
            'discount_idr' => $this->post('discount_idr'),
            'grand_total' => $this->post('grand_total'),
            'is_temp' => $this->post('is_temp'),
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => null,
            'created_by' => 1,
            'updated_by' => 1
        ];

        if ($this->Cashiers_api->postCashierSales($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'OK',
                'data' => 'new cashier_sales has been created.'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->set_response([
                'status' => FALSE,
                'message' => 'failed new created data',
                'data' => 'NULL'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function carts_post()
    {
        $data = [
            'owner_id' => $this->post('owner_id'),
            'outlet_id' => $this->post('outlet_id'),
            'cashier_sale_id' => $this->post('cashier_sale_id'),
            'product_info_id' => $this->post('product_info_id'),
            'buy_price' => $this->post('buy_price'),
            'sell_price' => $this->post('sell_price'),
            'qty' => $this->post('qty'),
            'discount_persen' => $this->post('discount_persen'),
            'discount_idr' => $this->post('discount_idr'),
            'variant_price' => $this->post('variant_price'),
            'total_price' => $this->post('total_price'),
            'note' => $this->post('note'),
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => null,
            'created_by' => 1,
            'updated_by' => 1
        ];

        if ($this->Cashiers_api->postCashierCarts($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'OK',
                'data' => 'new cashier_carts has been created.'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->set_response([
                'status' => FALSE,
                'message' => 'failed new created data',
                'data' => 'NULL'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
