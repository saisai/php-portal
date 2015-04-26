<?php

class Model {
    var $model;
    var $fields;
    var $conn;
    public function __construct($model, $fields) {
        $this->model = $model;
        $this->fields = $fields;
        $this->conn = new PDO('mysql:host=localhost;dbname=new_hrms;charset=utf8', 'root', 'password');
    }

    public function getall() {

    }

    public function get($id) {
        $sqlarr = array("SELECT",
            implode(',',$this->fields),
            "FROM",
            $this->model,
            "WHERE",
            "slno='{$id}'"
        );
        $sql = implode(" ",$sqlarr);
        //echo $sql;
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

class EmployeeModel extends Model {
    var $model = 'employee';
    var $fields = array(
        'slno',
        'center_code',
        'id as emp_id',
        'name',
        'middle_name',
        'surname',
        'short_code',
        'father_name',
        'mother_name',
        'DOB',
        'year_of_passing',
        'sex',
        'martial_status',
        'department',
        'designation',
        'client_name',
        'project_name',
        'passport_no',
        'passport_doi',
        'passport_doe',
        'bc_verfication',
        'bc_remarks',
        'blood_group',
        'appointment_type',
        'doj',
        'dol',
        'leaving_reason',
        'religion',
        'caste',
        'emergency_contact',
        'secondary_contact',
        'mobile_2',
        'msys_email',
        'location',
        'employees_number',
        'emails',
        'acount_no',
        'reporting_to',
        'reporting_to_name',
        'user_id',
        'password',
        'msys_reference',
        'temp_address',
        'perm_address',
        'address_3',
        'temp_city',
        'perm_city',
        'perm_state',
        'temp_state',
        'country',
        'tel_no_',
        'mobile_no_',
        'email_id',
        'bank_name',
        'is_chairman_list',
        'bank_branch',
        'bank_account_no',
        'pan_number',
        'pf_no_',
        'isfc_code',
        'ctc',
        'age',
        'photo',
        'skill_set',
        'data_log',
        'is_manager',
        'avail_cl',
        'is_resourse',
        'blocked',
        'is_employee',
        'ts'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields);
    }

    
}