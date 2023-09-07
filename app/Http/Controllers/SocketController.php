<?php

namespace App\Http\Controllers;

use App\WebSocket\Socket;
use Illuminate\Http\Request;

class SocketController extends Controller
{
    private $socket;

    public function __construct()
    {
        $socket = new Socket("127.0.0.1", "8989");
    }

    public function testSocket()
    {

    }
}
