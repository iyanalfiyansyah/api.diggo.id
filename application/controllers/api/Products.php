<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Products extends REST_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->load->model('Products_api');
    }

    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            $products = $this->Products_api->getProducts();
        } else {
            $products = $this->Products_api->getProducts($id);
        }

        if ($products) {
            $ss = URL_FRONT;
            $arr_replace[''] = '';
            //            
            foreach ($products as $key => $value) {
                $products[$key]['product_image'] = URL_FRONT . 'assets/img/products/' . $products[$key]['product_image'];
            }
            $this->response([
                'status' => true,
                'message' => 'OK',
                'data' => $products
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => FALSE,
                'message' => 'ID could not be found',
                'data' => 'NULL'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
