// API Configuration
// All server URLs are configured via environment variables

const API_BASE_URL = process.env.REACT_APP_API_BASE_URL || 'http://localhost:8000';
const SOCKET_URL = process.env.REACT_APP_SOCKET_URL || 'http://localhost:4000';

// API Endpoints
export const API_ENDPOINTS = {
  // Accidents
  ACCIDENTS: `${API_BASE_URL}/endpoints/accidents.php`,
  
  // Hospitals
  HOSPITALS: `${API_BASE_URL}/endpoints/hospitals.php`,
  HOSPITALS_NEAREST: (lat, lng) => `${API_BASE_URL}/endpoints/hospitals.php?lat=${lat}&lng=${lng}`,
  
  // Notifications
  NOTIFICATIONS: `${API_BASE_URL}/endpoints/notifications.php`,
};

// Socket.IO Configuration
export const SOCKET_CONFIG = {
  URL: SOCKET_URL,
  OPTIONS: {
    transports: ['websocket', 'polling'],
  },
};

// Export base URLs for direct use if needed
export { API_BASE_URL, SOCKET_URL };

