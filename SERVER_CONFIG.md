# Server Configuration Guide

This document contains all server URLs and endpoints for the Accident Alert System.

## Server URLs

### 1. Backend API (PHP REST API)
**Default Local Development:** `http://localhost:8000`

**Endpoints:**
- `GET /endpoints/accidents.php` - Get all accidents
- `POST /endpoints/accidents.php` - Create new accident
- `PUT /endpoints/accidents.php` - Update accident status
- `GET /endpoints/hospitals.php` - Get all hospitals
- `GET /endpoints/hospitals.php?lat={lat}&lng={lng}` - Get nearest hospitals
- `POST /endpoints/hospitals.php` - Create new hospital
- `POST /endpoints/notifications.php` - Send notification

**Configuration:**
- Set via environment variable: `REACT_APP_API_BASE_URL` (frontend)
- Or update `frontend/src/config/api.js` directly

### 2. Realtime Server (Node.js Socket.IO)
**Default Local Development:** `http://localhost:4000`

**Endpoints:**
- `GET /health` - Health check
- `POST /update` - Broadcast realtime update

**Socket.IO Events:**
- `connect` - Client connected
- `update` - Realtime update received
- `join` - Join a room
- `leave` - Leave a room

**Configuration:**
- Set via environment variable: `REACT_APP_SOCKET_URL` (frontend)
- Set via environment variable: `PORT` (realtime server)
- Set via environment variable: `CORS_ORIGIN` (realtime server)
- Optional: `REDIS_URL` for distributed deployments

### 3. Frontend (React)
**Default Local Development:** `http://localhost:3000` (if using Create React App)

## Production Deployment

### Setting Up Production URLs

1. **Frontend Configuration:**
   ```bash
   cd frontend
   cp .env.example .env
   # Edit .env and set your production URLs:
   # REACT_APP_API_BASE_URL=https://api.yourdomain.com
   # REACT_APP_SOCKET_URL=https://realtime.yourdomain.com
   ```

2. **Realtime Server Configuration:**
   ```bash
   cd realtime
   cp .env.example .env
   # Edit .env and set:
   # PORT=4000
   # CORS_ORIGIN=https://yourdomain.com
   # REDIS_URL=redis://your-redis-server:6379 (optional)
   ```

3. **Backend API:**
   - Deploy `backend/api` to your PHP web server
   - Update CORS headers if needed in each endpoint file
   - Ensure MySQL database is accessible

## Environment Variables Reference

### Frontend (.env)
- `REACT_APP_API_BASE_URL` - Backend API base URL
- `REACT_APP_SOCKET_URL` - Realtime Socket.IO server URL

### Realtime Server (.env)
- `PORT` - Server port (default: 4000)
- `CORS_ORIGIN` - Allowed CORS origins (comma-separated or * for all)
- `REDIS_URL` - Redis connection URL (optional)

## Quick Start (Local Development)

1. **Backend API:**
   ```bash
   php -S localhost:8000 -t backend/api
   ```

2. **Realtime Server:**
   ```bash
   cd realtime
   npm install
   node server.js
   ```

3. **Frontend:**
   ```bash
   cd frontend
   npm install
   npm start
   ```

## Testing Server URLs

- **Backend Health:** `curl http://localhost:8080/endpoints/hospitals.php`
- **Realtime Health:** `curl http://localhost:4000/health`
- **Socket.IO:** Connect via `http://localhost:4000` using Socket.IO client

## Notes

- All CORS is currently set to `*` (allow all origins) - update for production
- Database configuration is in `backend/api/config/db.php`
- Make sure to update `.env` files with your actual production URLs before deploying

