<?php
include_once "model.cls.php";
class Documents {
    public function fetch($empid, $filter) {
        $doc = new DocumentsModel;
        $result = $doc->getall($empid);
        return array(
            'result'=>$result,
            'total' => count($result)
        );
    }
    
    public function fetch_all($id, $filter) {

    }
}
