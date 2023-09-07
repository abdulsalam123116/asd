<?php

// app/WebSocket/Chat.php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $conn)
    {
        // Logic for when a new WebSocket connection is opened
        echo "WebSocket connection opened successfully for connection ID: {$conn->resourceId}\n";

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Handle incoming WebSocket messages
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Logic for when a WebSocket connection is closed
        echo "WebSocket connection closed for connection ID: {$conn->resourceId}\n";

    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Handle WebSocket errors
        echo "WebSocket error for connection ID: {$conn->resourceId}, Error: {$e->getMessage()}\n";

    }
}
