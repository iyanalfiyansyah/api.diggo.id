<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Login extends REST_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->load->model('Employees_api');
    }

    public function index_get()
    {
        $email = $this->get('email');
        $password = $this->get('password');

        if ($email) {
            $check_email = $this->email_check($email);
            //
            if ($check_email) {
                if ($password) {
                    $check_password = $this->password_check($password);
                    //
                    if ($check_password) {
                        $this->auth();
                    } else {
                        $this->set_response([
                            'status' => FALSE,
                            'data' => 'NULL',
                            'message' => 'Password could Found'
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'data' => 'NULL',
                        'message' => 'Password could not Empty'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'data' => 'NULL',
                    'message' => 'Email could Found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->set_response([
                'status' => FALSE,
                'data' => 'NULL',
                'message' => 'Email could not Empty'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function email_check($email)
    {
        $check = $this->Employees_api->check_login($email);
        if ($check->num_rows() == 0) {
            return false;
        }
        return true;
    }

    public function password_check($password)
    {
        $email = $this->get('email');
        $check = $this->Employees_api->check_password($email, $password);
        if ($check === 500) {
            return false;
        } elseif ($check === 404) {
            return false;
        } elseif ($check === false) {
            return false;
        } elseif ($check === true) {
            return true;
        } else {
            return false;
        }
    }

    public function auth()
    {
        $email = $this->get('email');
        $check = $this->Employees_api->check_login_arr($email);

        if ($check) {
            $this->response([
                'status' => TRUE,
                'data' => $check,
                'message' => 'OK'
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => FALSE,
                'data' => 'NULL',
                'message' => 'Login Failed'
            ], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }
}
