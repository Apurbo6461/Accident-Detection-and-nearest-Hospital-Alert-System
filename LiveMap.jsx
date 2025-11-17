import React, { useEffect, useState, useRef } from 'react';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import io from 'socket.io-client';
import { SOCKET_CONFIG } from '../config/api';

const ambulanceIcon = new L.Icon({
  iconUrl: '/icons/ambulance.png',
  iconSize: [32, 32],
  iconAnchor: [16, 16]
});

export default function LiveMap({ center = [23.7808, 90.4071], zoom = 13 }) {
  const [ambulances, setAmbulances] = useState({});
  const socketRef = useRef(null);

  useEffect(() => {
    const socket = io(SOCKET_CONFIG.URL, SOCKET_CONFIG.OPTIONS);
    socketRef.current = socket;

    socket.on('connect', () => {
      console.log('Connected to realtime server', socket.id);
    });

    socket.on('update', (payload) => {
      if (payload.type === 'ambulance') {
        setAmbulances(prev => ({
          ...prev,
          [payload.id]: { ...prev[payload.id], ...payload }
        }));
      }
    });

    socket.on('disconnect', () => console.log('Socket disconnected'));

    return () => {
      socket.disconnect();
    };
  }, []);

  const markers = Object.values(ambulances).map(a => (
    <Marker key={a.id} position={[a.lat, a.lng]} icon={ambulanceIcon}>
      <Popup>
        <div>
          <b>Ambulance #{a.id}</b><br/>
          Status: {a.status}<br/>
          ETA: {a.eta_minutes ? `${a.eta_minutes} min` : '—'}
        </div>
      </Popup>
    </Marker>
  ));

  return (
    <MapContainer center={center} zoom={zoom} style={{ height: '600px', width: '100%' }}>
      <TileLayer
        attribution='&copy; OpenStreetMap contributors'
        url='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
      />
      {markers}
    </MapContainer>
  );
}
