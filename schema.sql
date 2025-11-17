-- MySQL schema for Accident Detection System
CREATE DATABASE IF NOT EXISTS accident_alert_db;
USE accident_alert_db;

CREATE TABLE users (
  user_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(150),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vehicles (
  vehicle_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  plate VARCHAR(20) UNIQUE,
  model VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE hospitals (
  hospital_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(150),
  address VARCHAR(300),
  lat DOUBLE NOT NULL,
  lng DOUBLE NOT NULL,
  capacity INT DEFAULT 0,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE ambulances (
  ambulance_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  hospital_id BIGINT NOT NULL,
  plate VARCHAR(50),
  status ENUM('available','on-duty','unavailable') DEFAULT 'available',
  lat DOUBLE,
  lng DOUBLE,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id) ON DELETE CASCADE
);

CREATE TABLE accidents (
  accident_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id BIGINT,
  occ_time DATETIME NOT NULL,
  lat DOUBLE NOT NULL,
  lng DOUBLE NOT NULL,
  severity ENUM('low','medium','high') DEFAULT 'medium',
  status ENUM('reported','alerted','ambulance_assigned','resolved','closed') DEFAULT 'reported',
  speed FLOAT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_occ_time (occ_time),
  INDEX idx_status (status),
  FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL
);

CREATE TABLE accident_reports (
  report_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  accident_id BIGINT NOT NULL,
  created_by BIGINT,
  note TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (accident_id) REFERENCES accidents(accident_id) ON DELETE CASCADE
);

CREATE TABLE hospital_alerts (
  alert_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  accident_id BIGINT NOT NULL,
  hospital_id BIGINT NOT NULL,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  acknowledged_at TIMESTAMP NULL,
  status ENUM('sent','acknowledged','declined') DEFAULT 'sent',
  distance_m FLOAT,
  INDEX idx_accident_hospital (accident_id, hospital_id),
  FOREIGN KEY (accident_id) REFERENCES accidents(accident_id) ON DELETE CASCADE,
  FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id) ON DELETE CASCADE
);

CREATE TABLE notifications (
  notification_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  recipient_type ENUM('user','hospital','ambulance'),
  recipient_id BIGINT,
  accident_id BIGINT,
  message TEXT,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('pending','sent','failed') DEFAULT 'pending'
);

CREATE TABLE ambulance_assignments (
  assignment_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  accident_id BIGINT NOT NULL,
  ambulance_id BIGINT NOT NULL,
  hospital_id BIGINT DEFAULT NULL,
  assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  eta_minutes INT DEFAULT NULL,
  status ENUM('assigned','enroute','arrived','completed','cancelled') DEFAULT 'assigned',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (accident_id) REFERENCES accidents(accident_id) ON DELETE CASCADE,
  FOREIGN KEY (ambulance_id) REFERENCES ambulances(ambulance_id) ON DELETE CASCADE
);
