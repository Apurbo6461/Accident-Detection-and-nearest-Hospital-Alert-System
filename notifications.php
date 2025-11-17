<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';

class NotificationModel {
  private $conn;
  private $table = 'notifications';
  public function __construct($db) { $this->conn = $db; }
  public function send($recipient_type, $recipient_id, $accident_id, $message) {
    $query = "INSERT INTO notifications (recipient_type, recipient_id, accident_id, message, status) VALUES (:recipient_type, :recipient_id, :accident_id, :message, 'pending')";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':recipient_type', $recipient_type);
    $stmt->bindParam(':recipient_id', $recipient_id);
    $stmt->bindParam(':accident_id', $accident_id);
    $stmt->bindParam(':message', $message);
    return $stmt->execute();
  }
  public function readPending() {
    $query = "SELECT * FROM notifications WHERE status = 'pending' ORDER BY sent_at DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

require_once __DIR__ . '/../config/db.php';
$database = new Database();
$db = $database->getConnection();
$nm = new NotificationModel($db);

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
  case 'POST':
    $data = json_decode(file_get_contents('php://input'));
    if ($nm->send($data->recipient_type, $data->recipient_id, $data->accident_id, $data->message)) {
      echo json_encode(['message'=>'Notification created']);
    } else {
      echo json_encode(['error'=>'Failed to create notification']);
    }
    break;
  case 'GET':
    echo json_encode($nm->readPending());
    break;
  default:
    echo json_encode(['error'=>'Unsupported method']);
}
?>