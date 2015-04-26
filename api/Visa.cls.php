<?php
include_once "model.cls.php";
class Visa {
    public function fetch($empid, $filter) {
        $doc = new VisaModel;
        $result = $doc->getall($empid);
        return array(
            'result'=>$result,
            'total' => count($result)
        );
    }
    
    public function fetch_all($id, $filter) {

    }
}
