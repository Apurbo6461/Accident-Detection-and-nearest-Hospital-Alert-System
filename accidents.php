<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Accident.php';

$database = new Database();
$db = $database->getConnection();

$accident = new Accident($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    $stmt = $accident->readAll();
    $accidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($accidents);
    break;

  case 'POST':
    $data = json_decode(file_get_contents("php://input"));
    $accident->vehicle_id = $data->vehicle_id ?? null;
    $accident->occ_time = $data->occ_time;
    $accident->lat = $data->lat;
    $accident->lng = $data->lng;
    $accident->severity = $data->severity ?? 'medium';

    if ($accident->create()) {
      // simple inline call: alert nearest hospitals (if function exists)
      if (function_exists('alertNearestHospitals')) {
        alertNearestHospitals($db, $accident->accident_id, $accident->lat, $accident->lng);
      }
      echo json_encode(["message" => "Accident logged successfully", "id" => $accident->accident_id]);
    } else {
      echo json_encode(["error" => "Unable to log accident"]);
    }
    break;

  case 'PUT':
    $data = json_decode(file_get_contents("php://input"));
    $accident->accident_id = $data->accident_id;
    $accident->status = $data->status;
    if ($accident->updateStatus()) {
      echo json_encode(["message" => "Status updated"]);
    } else {
      echo json_encode(["error" => "Update failed"]);
    }
    break;

  default:
    echo json_encode(["error" => "Unsupported request method"]);
}
?>