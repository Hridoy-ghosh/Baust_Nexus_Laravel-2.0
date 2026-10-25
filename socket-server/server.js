const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

io.on('connection', (socket) => {
    console.log('⚡ [BAUST Nexus Real-Time Gateway] Client connected:', socket.id);

    socket.on('disconnect', () => {
        console.log('⚡ [BAUST Nexus Real-Time Gateway] Client disconnected:', socket.id);
    });

    socket.on('publish_notice', (data) => {
        console.log('📢 Real-time notice broadcasted:', data.title);
        io.emit('notice_broadcast', data);
    });

    socket.on('post_query', (data) => {
        console.log('💬 Real-time query broadcasted:', data.title);
        io.emit('query_broadcast', data);
    });
});

app.get('/status', (req, res) => {
    res.json({
        status: 'online',
        service: 'BAUST Nexus Socket.IO Gateway',
        timestamp: new Date().toISOString()
    });
});

app.post('/api/notify', (req, res) => {
    const { type, payload } = req.body;
    io.emit(type, payload);
    return res.json({ success: true, delivered: true });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`=======================================================`);
    console.log(`🚀 BAUST Nexus Real-Time Gateway listening on port ${PORT}`);
    console.log(`=======================================================`);
});
