<?php
class Hospital {
  private $conn;
  private $table = 'hospitals';

  public $hospital_id;
  public $name;
  public $phone;
  public $email;
  public $address;
  public $lat;
  public $lng;
  public $capacity;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function create() {
    $query = "INSERT INTO hospitals (name, phone, email, address, lat, lng, capacity) VALUES (:name, :phone, :email, :address, :lat, :lng, :capacity)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":phone", $this->phone);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":lat", $this->lat);
    $stmt->bindParam(":lng", $this->lng);
    $stmt->bindParam(":capacity", $this->capacity);
    return $stmt->execute();
  }

  public function readAll() {
    $query = "SELECT * FROM hospitals ORDER BY name ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function nearest($lat, $lng, $limit = 3) {
    $query = "SELECT hospital_id, name, phone, email, address, lat, lng, capacity,
      ( 6371000 * acos( cos( radians(:lat) ) * cos( radians(lat) ) * cos( radians(lng) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians(lat) ) ) ) AS distance_m
      FROM hospitals
      ORDER BY distance_m ASC
      LIMIT :limit";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(":lat", $lat);
    $stmt->bindValue(":lng", $lng);
    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }
}
?>