<?php

class Employees_api extends CI_Model
{
    public function getEmployees($id = null)
    {
        if ($id === null) {
            return $this->db->get('employees')->result_array();
        } else {
            return $this->db->get_where('employees', ['id' => $id])->result_array();
        }
    }

    public function check_login($email)
    {
        $this->db->select([
            'employees.owner_id',
            'employees.outlet_id',
            'employees.id as employee_id',
            'employees.name',
            'employees.email',
            'employees.password',
            'employees.type_access_id',
            //'owners.is_active',
            //'owners.is_wizard',
            'employees.status_employee',
            'param_type_access.name as type_access_name',
        ]);
        //$this->db->join('owners', 'owners.id = employees.owner_id', 'left');
        $this->db->join('param_type_access', 'param_type_access.id = employees.type_access_id', 'left');
        $this->db->where('employees.type_access_id', 1);
        $this->db->where('employees.status_employee', 'on');
        $this->db->where('employees.deleted_at', null);
        $this->db->where('employees.email', $email);
        return $this->db->get('employees');
    }

    public function check_login_arr($email)
    {
        $this->db->select([
            'employees.owner_id',
            'employees.outlet_id',
            'employees.id as employee_id',
            'employees.name',
            'employees.email',
            'employees.password',
            'employees.type_access_id',
            //'owners.is_active',
            //'owners.is_wizard',
            'employees.status_employee',
            'param_type_access.name as type_access_name',
        ]);
        //$this->db->join('owners', 'owners.id = employees.owner_id', 'left');
        $this->db->join('param_type_access', 'param_type_access.id = employees.type_access_id', 'left');
       $this->db->where('employees.type_access_id', 1);
        $this->db->where('employees.status_employee', 'on');
        $this->db->where('employees.deleted_at', null);
        $this->db->where('employees.email', $email);
        return $this->db->get('employees')->result_array();
    }

    public function check_password($email, $password)
    {
        $exec = $this->check_login($email);

        if (!$exec) {
            return 500;
        }

        if ($exec->num_rows() == 0) {
            return 404;
        }

        $password_db = $exec->row()->password;

        if (password_verify($password, $password_db)) {
            return true;
        }

        return false;
    }
}
