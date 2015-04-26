<?php
include_once "model.cls.php";
class Experience {
    public function fetch($empid, $filter) {
        $doc = new ExperienceModel;
        $result = $doc->getall($empid);
        return array(
            'result'=>$result,
            'total' => count($result)
        );
    }
    
    public function fetch_all($id, $filter) {

    }
}
