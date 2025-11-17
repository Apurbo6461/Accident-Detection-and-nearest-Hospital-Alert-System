// realtime/server.js
require('dotenv').config();
const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const bodyParser = require('body-parser');

const REDIS_URL = process.env.REDIS_URL || null;
const PORT = process.env.PORT || 4000;

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: process.env.CORS_ORIGIN || '*',
    methods: ['GET','POST']
  }
});

app.use(bodyParser.json());

let redisClient = null;
let redisSubscriber = null;

io.on('connection', (socket) => {
  console.log(`Realtime: client connected ${socket.id}`);

  socket.on('join', (room) => {
    if (room) socket.join(room);
  });

  socket.on('leave', (room) => {
    if (room) socket.leave(room);
  });

  socket.on('disconnect', (reason) => {
    console.log(`Realtime: client disconnected ${socket.id} (${reason})`);
  });
});

app.get('/health', (req, res) => res.json({ status: 'ok' }));

app.post('/update', (req, res) => {
  const payload = req.body;
  if (!payload || !payload.type) return res.status(400).json({ error: 'Invalid payload' });

  try {
    io.emit('update', payload);
    if (payload.type && payload.id) {
      io.to(`${payload.type}:${payload.id}`).emit('update', payload);
    }
    return res.json({ ok: true });
  } catch (err) {
    console.error('Realtime /update error', err);
    return res.status(500).json({ error: 'Internal error' });
  }
});

async function initRedis() {
  if (!REDIS_URL) return;
  try {
    const { createClient } = require('redis');
    redisClient = createClient({ url: REDIS_URL });
    await redisClient.connect();
    redisSubscriber = redisClient.duplicate();
    await redisSubscriber.connect();

    await redisSubscriber.subscribe('ambulance_updates', (message) => {
      try {
        const payload = JSON.parse(message);
        io.emit('update', payload);
        if (payload.type && payload.id) io.to(`${payload.type}:${payload.id}`).emit('update', payload);
      } catch (e) {
        console.error('Invalid redis message', e);
      }
    });

    console.log('Realtime: subscribed to Redis ambulance_updates');
  } catch (e) {
    console.error('Realtime: failed to init Redis', e);
  }
}

function shutdown() {
  console.log('Realtime: shutting down...');
  if (redisSubscriber) redisSubscriber.unsubscribe('ambulance_updates').catch(()=>{});
  if (redisClient) redisClient.quit().catch(()=>{});
  server.close(() => process.exit(0));
}
process.on('SIGINT', shutdown);
process.on('SIGTERM', shutdown);

initRedis().then(() => {
  server.listen(PORT, () => {
    console.log(`Realtime server listening on port ${PORT}`);
  });
});

module.exports = { app, io };
