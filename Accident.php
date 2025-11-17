<?php
class Accident {
  private $conn;
  private $table = 'accidents';

  public $accident_id;
  public $vehicle_id;
  public $occ_time;
  public $lat;
  public $lng;
  public $severity;
  public $status;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function create() {
    $query = "INSERT INTO " . $this->table . " (vehicle_id, occ_time, lat, lng, severity) VALUES (:vehicle_id, :occ_time, :lat, :lng, :severity)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":vehicle_id", $this->vehicle_id);
    $stmt->bindParam(":occ_time", $this->occ_time);
    $stmt->bindParam(":lat", $this->lat);
    $stmt->bindParam(":lng", $this->lng);
    $stmt->bindParam(":severity", $this->severity);
    if ($stmt->execute()) {
      $this->accident_id = $this->conn->lastInsertId();
      return true;
    }
    return false;
  }

  public function readAll($limit = 50) {
    $query = "SELECT * FROM " . $this->table . " ORDER BY occ_time DESC LIMIT :limit";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }

  public function updateStatus() {
    $query = "UPDATE " . $this->table . " SET status = :status WHERE accident_id = :accident_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":accident_id", $this->accident_id);
    return $stmt->execute();
  }
}
?>