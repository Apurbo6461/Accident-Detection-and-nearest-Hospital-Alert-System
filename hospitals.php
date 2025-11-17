<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Hospital.php';

$database = new Database();
$db = $database->getConnection();
$hospital = new Hospital($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    if (isset($_GET['lat']) && isset($_GET['lng'])) {
      $stmt = $hospital->nearest($_GET['lat'], $_GET['lng']);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode(["nearest" => $rows]);
    } else {
      $stmt = $hospital->readAll();
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    break;

  case 'POST':
    $data = json_decode(file_get_contents("php://input"));
    $hospital->name = $data->name;
    $hospital->phone = $data->phone;
    $hospital->email = $data->email;
    $hospital->address = $data->address;
    $hospital->lat = $data->lat;
    $hospital->lng = $data->lng;
    $hospital->capacity = $data->capacity ?? 0;

    if ($hospital->create()) {
      echo json_encode(["message" => "Hospital added successfully"]);
    } else {
      echo json_encode(["error" => "Unable to add hospital"]);
    }
    break;

  default:
    echo json_encode(["error" => "Unsupported method"]);
}
?>