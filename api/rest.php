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
switch($_SERVER['REQUEST_METHOD']) {
    case 'GET' && $id:
        http_response_code(200);
        echo json_encode($cls->fetch($id, $filter));
        break;
    case 'GET' && !$id:
        echo json_encode($cls->fetch_all($filter));
        break;
    case 'POST':
        echo json_encode($cls->create());
        break;
    case 'PUT' && $id:
        echo json_encode($cls->update());
        break;
    case 'DELETE' && $id:
        echo json_encode($cls->delete());
        break;
    default:
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

?>
