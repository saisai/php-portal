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

    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        $qlfy = new QualificationModel;
        $qlfy->create($data);
        return array("msg"=>"Ok Updated");
    }
}
