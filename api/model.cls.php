<?php

class Model {
    var $model;
    var $fields;
    var $conn;
    var $primary;
    public function __construct($model, $fields, $primary='slno') {
        $this->model = $model;
        $this->fields = $fields;
        $this->primary = $primary;
        $this->conn = new PDO('mysql:host=localhost;dbname=new_hrms;charset=utf8', 'root', 'password');
    }

    public function getall($id) {
        $sqlarr = array("SELECT",
            implode(',',$this->fields),
            "FROM",
            $this->model,
            "WHERE",
            "{$this->primary}='{$id}'"
        );
        $sql = implode(" ",$sqlarr);
        //echo $sql;
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $sqlarr = array("SELECT",
            implode(',',$this->fields),
            "FROM",
            $this->model,
            "WHERE",
            "{$this->primary}='{$id}'"
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
        'slno', 'center_code', 'id as emp_id', 'name', 'middle_name', 'surname',
        'short_code', 'father_name', 'mother_name', 'DOB', 'year_of_passing', 'sex',
        'martial_status', 'department', 'designation', 'client_name', 'project_name',
        'passport_no', 'passport_doi', 'passport_doe', 'bc_verfication', 'bc_remarks',
        'blood_group', 'appointment_type', 'doj', 'dol', 'leaving_reason',
        'religion', 'caste', 'emergency_contact', 'secondary_contact', 'mobile_2',
        'msys_email', 'location', 'employees_number', 'emails', 'acount_no',
        'reporting_to', 'reporting_to_name', 'user_id', 'password', 'msys_reference',
        'temp_address', 'perm_address', 'address_3', 'temp_city', 'perm_city',
        'perm_state', 'temp_state', 'country', 'tel_no_', 'mobile_no_',
        'email_id', 'bank_name','bank_branch', 'bank_account_no',
        'pan_number', 'pf_no_', 'isfc_code', 'ctc', 'age',
        'photo', 'skill_set', 'data_log', 'is_manager', 'avail_cl', 'is_resourse',
        'blocked', 'is_employee', 'ts'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields);
    }
}

class DocumentsModel extends Model {
    var $model = 'emp_documents';
    var $fields = array(
        'slno', 'code', 'name', 'course', 'emp_id',
        'line_no_', 'doc_type', 'doc_file', 'upload_date', 'remarks',
        'hr_remarks', 'in_hand', 'return_date', 'verified',
        'returned', 'ts'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields, 'emp_id');
    }
}