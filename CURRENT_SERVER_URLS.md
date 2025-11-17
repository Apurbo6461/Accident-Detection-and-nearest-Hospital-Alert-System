# Current Server URLs Configuration

## 🔗 Active Server URLs

### Backend API (PHP REST API)
**URL:** `http://localhost:8000`

**Endpoints:**
- `http://localhost:8000/endpoints/accidents.php` - Accidents CRUD
- `http://localhost:8000/endpoints/hospitals.php` - Hospitals CRUD
- `http://localhost:8000/endpoints/notifications.php` - Notifications

### Realtime Server (Socket.IO)
**URL:** `http://localhost:4000`

**Endpoints:**
- `http://localhost:4000/health` - Health check
- `http://localhost:4000` - Socket.IO connection endpoint

### Frontend Configuration
**Socket URL:** `http://localhost:4000` (from `REACT_APP_SOCKET_URL`)
**API Base URL:** `http://localhost:8000` (from `REACT_APP_API_BASE_URL`)

---

## 📝 To Use Production URLs

1. **Copy environment files:**
   ```bash
   cp frontend/.env.example frontend/.env
   cp realtime/.env.example realtime/.env
   ```

2. **Edit `frontend/.env`:**
   ```env
   REACT_APP_API_BASE_URL=https://your-api-domain.com
   REACT_APP_SOCKET_URL=https://your-realtime-domain.com
   ```

3. **Edit `realtime/.env`:**
   ```env
   PORT=4000
   CORS_ORIGIN=https://your-frontend-domain.com
   REDIS_URL=redis://your-redis-url (optional)
   ```

4. **Restart services** after updating environment variables.

---

## 📚 Full Documentation

See `SERVER_CONFIG.md` for complete configuration guide.

