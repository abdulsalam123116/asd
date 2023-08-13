<template>
    <div>
        <!-- Your receipt content and print button here -->
        <div id="receipt">
            <img
                src="../assets/images/pharmacy-Receipt-logo.png"
                alt="Pharmacy Logo"
                id="logo"
            />

            <div class="header">
                <h2>Tax Invoice</h2>
                <p>{{ storeName }}</p>
                <p>{{ storeAddress }}</p>
                <p>Phone: 06 537 9227</p>
            </div>

            <div class="customer-details">
                <p>Date: {{ formattedDate }}</p>
                <p>Invoice No.: {{ invoiceNumber }}</p>
                <p>Customer: {{ customerName }}</p>
                <p>Employee: {{ employeeName }}</p>
            </div>

            <!-- Item Table -->
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through itemList and generate rows -->
                    <!-- For example: -->
                    <!-- <tr v-for="(item, index) in itemList" :key="index"> -->
                    <!--   <td>{{ item.productName }}</td> -->
                    <!--   <td>{{ item.unit }}</td> -->
                    <!--   <td>{{ item.sellingPrice }} {{ currency }}</td> -->
                    <!--   <td>{{ item.subTotal }} {{ currency }}</td> -->
                    <!-- </tr> -->
                </tbody>
            </table>

            <div class="total">
                <h4>Net Total: {{ fixLength(totalBill) }}</h4>
                <!-- Loop through paymentList and generate payment rows -->
                <!-- For example: -->
                <!-- <p v-for="(item, index) in paymentList" :key="index">{{ item.paymentType }} : {{ item.transTotalAmount }} {{ currency }}</p> -->
                <h3>
                    Total: {{ fixLength(totalPaymentsReceived) }} {{ currency }}
                </h3>
                <p>Discount: {{ discountAmount }}%</p>
                <p>
                    Balance Due: {{ fixLength(paymentRounding) }} {{ currency }}
                </p>
            </div>

            <div class="footer">
                <p>Thank you for your visit, have a nice day!</p>
                <br />
                <p>By: Smart Link</p>
            </div>
        </div>

        <!-- Link to the print-styles.css file for printing -->
        <link
            rel="stylesheet"
            type="text/css"
            href="./print-styles.css"
            media="print"
        />

        <!-- Print button -->
        <Button label="Print Receipt" @click="printReceipt" />
    </div>
</template>
<script>
import { Button } from "primevue/button";
import Toaster from "../helpers/Toaster";
// ... Other imports ...

export default {
    name: "PrintReceiptDialog",
    components: {
        Button,
    },
    props: {
        props: {
            storeName: String,
            storeAddress: String,
            formattedDate: String,
            invoiceNumber: String,
            customerName: String,
            employeeName: String,
            itemList: Array,
            totalBill: Number,
            paymentList: Array,
            totalPaymentsReceived: Number,
            discountAmount: Number,
            paymentRounding: Number,
            currency: String,
        },
    },
    methods: {
        printReceipt() {
            // Create a new hidden window and write the receipt content into it
            const printWindow = window.open(
                "",
                "_blank",
                "width=800,height=auto"
            );
            printWindow.document.open();
            printWindow.document.write(this.$el.outerHTML); // Write the component's HTML
            printWindow.document.close();

            // Trigger the print dialog for the hidden window
            setTimeout(() => {
                printWindow.print();
            }, 500);
        },

        fixLength(value) {
            const num = Number(value);
            value = num.toFixed(2);
            return value;
        },

        fixLengthNumber(value) {
            const num = Number(value);
            value = num.toFixed(2);
            value = Number(value);
            return value;
        },
    },
};
</script>

<style scoped>
.receipt-container {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.receipt {
    width: 8cm;
    margin: 4cm auto;
    padding: 10px;
    border: 1px solid #ccc;
    background-color: #fff;
    box-shadow: 0px 0px 5px 2px #ccc;
}

.logo {
    width: 100%;
    height: 4cm;
    display: block;
    margin-bottom: 10px;
    border-radius: 5px;
}

.header {
    text-align: center;
    margin-bottom: 10px;
}

.customer-details {
    margin-bottom: 10px;
}

.item-table {
    width: 100%;
    border-collapse: collapse;
}

.item-table th,
.item-table td {
    border: 1px solid #ccc;
    padding: 5px;
    text-align: center;
}

.total {
    margin-top: 10px;
    text-align: right;
    color: #000;
}

.footer {
    margin-top: 20px;
    text-align: center;
}

/* Add your additional styles here */
</style>
