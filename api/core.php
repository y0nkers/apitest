<?php
require_once "../class/DbConnect.php";
require_once '../class/UserController.php';

$username = "root";
$password = "";

$db = new DbConnect($username, $password);
$controller = new UserController($db);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$pathInfo = explode('/', trim($_SERVER['PATH_INFO'], '/'));

header('Content-Type: application/json');

$response = ['status' => 400, 'message' => 'Bad Request'];

switch ($requestMethod) {
    case 'POST':
        if ($pathInfo[0] === 'user' && empty($pathInfo[1])) {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $controller->createUser($data);
        } elseif ($pathInfo[0] === 'auth') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $controller->authorizeUser($data);
        }
        break;

    case 'PUT':
        if ($pathInfo[0] === 'user' && !empty($pathInfo[1])) {
            $id = (int) $pathInfo[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $controller->updateUser($id, $data);
        }
        break;

    case 'DELETE':
        if ($pathInfo[0] === 'user' && !empty($pathInfo[1])) {
            $id = (int) $pathInfo[1];
            $response = $controller->deleteUser($id);
        }
        break;

    case 'GET':
        if ($pathInfo[0] === 'user' && !empty($pathInfo[1])) {
            $id = (int) $pathInfo[1];
            $response = $controller->getUser($id);
        }
        break;

    default:
        $response = ['status' => 405, 'message' => 'Method Not Allowed'];
        break;
}

http_response_code($response['status']);
echo json_encode($response);

$db->closeConnection();
?>
