<?php
include_once "model.cls.php";
class employee {
    public function fetch($id, $filter) {
        $emp = new EmployeeModel;
        return $emp->get($id);
    }
    
    public function fetch_all($id, $filter) {

    }
}
