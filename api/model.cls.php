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

    public function update($id, $data) {
        $updatefields = array();
        foreach ($data as $k => $v ) {
            $updatefields[] = "{$k} = '{$v}'";
        } 

        $sqlarr = array("UPDATE",
            $this->model,
            "SET",
            implode(',',$updatefields),
            "WHERE",
            "{$this->primary}='{$id}'"
        );
        $sql = implode(" ",$sqlarr);
        //echo $sql;
        $stmt = $this->conn->query($sql);
        return $stmt->rowCount();
    }
}

class EmployeeModel extends Model {
    var $model = 'employee';
    var $fields = array(
        'slno', 'center_code', 'id as emp_id', 'name', 'middle_name', 'surname',
        'short_code', 'father_name', 'mother_name', 'date_format(DOB,\'%d-%m-%Y\')as DOB', 'year_of_passing', 'sex',
        'martial_status', 'department', 'designation', 'client_name', 'project_name',
        'passport_no', 'passport_doi', 'passport_doe', 'bc_verfication', 'bc_remarks',
        'blood_group', 'appointment_type', 'date_format(doj,\'%d-%m-%Y\')as doj', 'dol', 'leaving_reason',
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
        parent::__construct($this->model,$this->fields,'emp_id');
    }

}

class DocumentsModel extends Model {
    var $model = 'emp_documents';
    var $fields = array(
        'slno', 'code', 'name', 
        'emp_id',
        'line_no_', 'doc_type', 'doc_file', 'upload_date', 'remarks',
        'hr_remarks', 'IF((in_hand = \'org\'),\'Original\',\'Copy\') as in_hand',
        'date_format(return_date,\'%d-%m-%Y\')as return_date',
         'IF((verified),\'Checked\',\'\') as verified',
        'IF((returned),\'Checked\',\'\') as returned',
        'IF((course = \'REGULAR\'),\'Regular\',\'Distance\') as course',
         'ts'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields, 'emp_id');
    }
}

class QualificationModel extends Model {
    var $model = 'emp_qualification';
    var $fields = array(
        'slno','emp_id','line_no_', 'upload_date', 'remarks', 'hr_remarks','ts',
        'board','type','course_name','total_mark','obtained_mark',
        'percentage','year_of_passing','grade'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields, 'emp_id');
    }
}

class VisaModel extends Model {
    var $model = 'emp_visa';
    var $fields = array(
        'slno','emp_id','line_no_', 'upload_date', 'remarks', 'hr_remarks','ts',
        'type','country','start_date','end_date'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields, 'emp_id');
    }
}

class ProjectModel extends Model {
    var $model = 'emp_projects';
    var $fields = array(
        'slno','emp_id','line_no_', 'upload_date', 'remarks', 'hr_remarks','ts',
        'code','name','reporting_to','is_completed'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields, 'emp_id');
    }
}

class ExperienceModel extends Model {
    var $model = 'emp_experience';
    var $fields = array(
        'slno','emp_id','line_no_', 'upload_date', 'remarks', 'hr_remarks','ts',
        'company_name','total_exp','doj','dol','designation','role','team_size','ctc'
    );
    public function __construct() {
        parent::__construct($this->model,$this->fields, 'emp_id');
    }
}