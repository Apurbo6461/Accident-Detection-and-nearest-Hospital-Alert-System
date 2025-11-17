# Accident Detection & Nearest Hospital Alert System

This repository contains a starter implementation for:
- PHP + MySQL backend (REST API) for accidents, hospitals, ambulances, and notifications
- Node.js realtime Socket.IO microservice for live ambulance tracking
- React frontend scaffold with a LiveMap component (Leaflet)
- SQL schema to create required tables

## Structure
- `backend/api/` - PHP REST API (models, endpoints)
- `backend/sql/` - SQL schema and seed example
- `realtime/` - Node.js realtime microservice (Socket.IO)
- `frontend/` - React scaffold (LiveMap component)
- `docker/` - example Dockerfile + docker-compose (optional)

## Quick start (local)
1. Import database:
   - `mysql -u root -p < backend/sql/schema.sql`
2. Start realtime server:
   - `cd realtime && npm install && node server.js`
3. Start PHP backend (use XAMPP / LAMP or php -S):
   - Place `backend/api` in your webroot or use `php -S localhost:8000 -t backend/api`
4. Start React frontend (optional):
   - `cd frontend && npm install && npm start`

See each folder for details.

