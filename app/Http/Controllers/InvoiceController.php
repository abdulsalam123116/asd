<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebSocket\InvoiceWebSocket;

class InvoiceController extends Controller
{
    public function sendInvoiceToCSharpApp(Request $request)
    {
        // ... (your existing code to build $invoiceData)
        // Sample data for the invoice
        $pharmacyName = "صيدلية الرحمة";
        $pharmacyAddress = "شارع العباسية، القاهرة";
        $pharmacyPhoneNumber = "01012345678";
        $invoiceNumber = "INV-12345";
        $invoiceDate = now();

        $customerName = "السيد أحمد علي";
        $customerAddress = "شارع المعز، القاهرة";
        $customerPhoneNumber = "01087654321";

        $productNames = ["باراسيتامول", "أموكسيسيلين", "إيبوبروفين"];
        $quantities = [2, 1, 3];
        $prices = [10.5, 20.0, 15.75];

        $invoiceDataBuilder = new \StringBuilder();
        $invoiceDataBuilder->appendLine("صيدلية: $pharmacyName");
        $invoiceDataBuilder->appendLine("العنوان: $pharmacyAddress");
        $invoiceDataBuilder->appendLine("هاتف: $pharmacyPhoneNumber");
        $invoiceDataBuilder->appendLine();
        $invoiceDataBuilder->appendLine("رقم الفاتورة: $invoiceNumber");
        $invoiceDataBuilder->appendLine("تاريخ الفاتورة: " . $invoiceDate->format("d/m/Y"));
        $invoiceDataBuilder->appendLine();
        $invoiceDataBuilder->appendLine("العميل: $customerName");
        $invoiceDataBuilder->appendLine("العنوان: $customerAddress");
        $invoiceDataBuilder->appendLine("هاتف: $customerPhoneNumber");
        $invoiceDataBuilder->appendLine();
        $invoiceDataBuilder->appendLine("تفاصيل المنتجات:");

        for ($i = 0; $i < count($productNames); $i++) {
            $invoiceDataBuilder->append("$productNames[$i] - الكمية: $quantities[$i] - السعر: $prices[$i] جنيه");
            $invoiceDataBuilder->appendLine();
        }

        $invoiceDataBuilder->appendLine();

        $totalAmount = array_sum(array_map(function ($quantity, $price) {
            return $quantity * $price;
        }, $quantities, $prices));

        $invoiceDataBuilder->append("إجمالي الفاتورة: " . number_format($totalAmount, 2) . " جنيه");

        $invoiceData = $invoiceDataBuilder->toString();


        // Send the invoice data to the C# application through WebSocket
        $webSocketServer = new InvoiceWebSocket();
        $webSocketServer->sendInvoiceData($invoiceData);

        return response()->json(['message' => 'تم إرسال الفاتورة بنجاح.']);
    }
}
