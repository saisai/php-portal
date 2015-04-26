<?php
list($url, $filter) = parseUri($_GET);
list($_, $class, $id) = explode('/', $url);
$class = ucfirst(strtolower($class));
if (!file_exists($class . '.cls.php')) {
    http_response_code(501);
    echo "{$class} class not implemented";
    exit();
}
spl_autoload_register(function ($class) {
    include_once $class . '.cls.php';
});
try {
    $cls = new $class;
} catch (Exception $e) {
    http_response_code(501);
    echo 'Error Message: ',  $e->getMessage(), "\n";
}
header('Content-Type: application/json');
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        http_response_code(200);
        if($id) echo json_encode($cls->fetch($id, $filter));
        else echo json_encode($cls->fetch_all($filter));
        break;
    case 'POST':
        http_response_code(201);
        echo json_encode($cls->create());
        break;
    case 'PUT':
        http_response_code(200);
        echo json_encode($cls->update($id));
        break;
    case 'DELETE' && $id:
        echo json_encode($cls->delete());
        break;
    default:
        http_response_code(501); 
        break;
}

function parseUri($urlparam) {
    $get = array();
    $url = '';
    foreach($urlparam as $k => $v) {
        if ($k=="_url") $url = $v;
        else $get[$k] = $v;
    }
    return array($url, $get);
}
