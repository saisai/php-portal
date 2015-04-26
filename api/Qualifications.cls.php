<?php
include_once "model.cls.php";
class Qualifications {
    public function fetch($empid, $filter) {
        $doc = new QualificationModel;
        $result = $doc->getall($empid);
        return array(
            'result'=>$result,
            'total' => count($result)
        );
    }
    
    public function fetch_all($id, $filter) {

    }
}
