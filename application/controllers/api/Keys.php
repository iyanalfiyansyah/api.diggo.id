<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Keys extends REST_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->load->model('Keys_api');
    }

    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            $keys = $this->Keys_api->getKeys();
        } else {
            $keys = $this->Keys_api->getKeys($id);
        }

        if ($keys) {
            $this->response([
                'status' => true,
                'message' => 'OK',
                'data' => $keys
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
