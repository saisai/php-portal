<?php
include_once "model.cls.php";
class Employee {
    public function fetch($id, $filter) {
        $emp = new EmployeeModel;
        return $emp->get($id);
    }
    
    public function fetch_all($id, $filter) {

    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        $emp = new EmployeeModel;
        $emp->update($id, $data);
        return array("msg"=>"Ok Updated");
    }
}
