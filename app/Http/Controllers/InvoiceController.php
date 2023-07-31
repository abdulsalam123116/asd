<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class InvoiceController extends Controller implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // لا يلزم فعل أي شيء عند فتح اتصال جديد
        // يمكنك إضافة أي تعليمات تريدها هنا

        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // لا يلزم فعل أي شيء هنا، لأننا لا نتوقع استقبال بيانات من التطبيق C# إلى Laravel
    }

    public function onClose(ConnectionInterface $conn)
    {
        // لا يلزم فعل أي شيء عند إغلاق اتصال
        // يمكنك إضافة أي تعليمات تريدها هنا

        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    // دالة لإرسال الفاتورة عبر الـ Socket إلى التطبيق المكتوب بلغة C#
    public function sendInvoiceToCSharpApp(Request $request)
    {
        // تفاصيل الفاتورة والمنتجات وغيرها من البيانات التي تحتاج إلى إرسالها إلى C#
        // قم بتعبئة هذه المتغيرات باحتياجاتك الخاصة

        $invoiceData = "بيانات الفاتورة هنا..."; // قم ببناء بيانات الفاتورة بشكل نصي هنا
        $socketMessage = json_encode(['invoiceData' => $invoiceData]);

        foreach ($this->clients as $client) {
            $client->send($socketMessage);
        }

        return response()->json(['message' => 'تم إرسال الفاتورة بنجاح.']);
    }
}
