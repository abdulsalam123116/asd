<?php
// app/WebSocket/InvoiceWebSocket.php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class InvoiceWebSocket implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Here, you can process any data received from the WebSocket client (if needed)
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    public function sendInvoiceData($invoiceData)
    {
        $socketMessage = json_encode(['invoiceData' => $invoiceData]);

        foreach ($this->clients as $client) {
            $client->send($socketMessage);
        }
    }
}
