const express = require("express");
const http = require("http");
const { Server } = require("socket.io");

const app = express();
const server = http.createServer(app);
const io = new Server(server);

app.use(express.static("public")); // Sirve archivos en la carpeta "public"

const PORT = 3000;


server.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
});