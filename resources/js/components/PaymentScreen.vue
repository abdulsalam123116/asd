<template>
    <Dialog
        v-model:visible="paymentDialog"
        :style="{ width: '100vw' }"
        :modal="true"
        :closable="true"
        position="top"
        @hide="confirmPaymentCancel"
        class="p-fluid p-m-0 p-dialog-maximized"
        :showHeader="true"
    >
        <template #header>
            <h6 class="p-dialog-titlebar p-dialog-titlebar-icon">
                <i class="pi pi-money-bill py-icon-color"></i> {{ dialogTilte }}
            </h6>
        </template>
        <!--row open-->
        <div class="row text-center">
            <div class="col-md-9">
                <p class="pay-size-bx">
                    <label class="py-span">
                        <img src="@/assets/pay/cash.png" />
                        Cash
                        <RadioButton v-model="paymentMethodType" value="Cash" />
                    </label>
                </p>
                <p class="pay-size-bx">
                    <label class="py-span">
                        <img src="@/assets/pay/manual.png" />
                        Card
                        <RadioButton
                            v-model="paymentMethodType"
                            value="Manual"
                        />
                    </label>
                </p>
                <p class="pay-size-bx" v-if="restriction != 'Yes'">
                    <label class="py-span">
                        <img src="@/assets/pay/paylater.png" />
                        Pay Later
                        <RadioButton
                            v-model="paymentMethodType"
                            value="Pay Later"
                        />
                    </label>
                </p>
            </div>
            <!-- Add input field for discount -->
            <div class="pay-size-bx">
                <label class="py-span badge bg-warning"
                    >Discount Amount %</label
                >
                <InputNumber
                    v-model="discountAmount"
                    @keypress="addDiscount(discountAmount)"
                />
            </div>
        </div>
        <div class="row py-description">
            <div
                style="height: 0.2em; background-color: #fff"
                class="col-md-12"
            >
                <ProgressBar
                    v-if="progressBar"
                    mode="indeterminate"
                    style="height: 0.2em"
                />
            </div>
            <div class="col-md-2 col-sm-12 content-height p-pl-1 p-pr-0">
                <h5>
                    ({{ currency }}) Amount Due
                    <input
                        type="text"
                        :value="fixLength(paymentRounding)"
                        readonly
                        class="form-control py-customize-bx mt-1 py-balance-due"
                    />
                </h5>
                <h5 class="mt-2">
                    ({{ currency }}) Tendered
                    <input
                        style="color: green"
                        type="number"
                        readonly
                        :value="fixLength(paymentAction.tendered)"
                        class="form-control py-customize-bx mt-1"
                    />
                </h5>
                <h5 class="mt-2">
                    ({{ currency }}) Change
                    <input
                        type="number"
                        readonly
                        :value="fixLength(changeAmount)"
                        class="form-control py-customize-bx mt-1"
                    />
                </h5>
                <h5 class="mt-2">
                    ({{ currency }}) Round Off
                    <input
                        type="number"
                        readonly
                        :value="fixLength(roundedAmt)"
                        class="form-control py-customize-bx mt-1"
                    />
                </h5>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="content-height">
                    <h5>
                        <i
                            class="pi pi-file-o py-icon-color"
                            aria-hidden="true"
                        ></i>
                        Key Pad
                    </h5>
                    <!-- keypad -->
                    <div
                        class="btn-group-vertical text-center"
                        role="group"
                        aria-label="Basic example"
                    >
                        <div class="btn-group btn-group-lg">
                            <button
                                type="button"
                                @click="amountNumpad(1)"
                                class="btn-numpad col-sm-4"
                            >
                                1
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad(2)"
                                class="btn-numpad col-sm-4"
                            >
                                2
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad(3)"
                                class="btn-numpad col-sm-4"
                            >
                                3
                            </button>
                        </div>
                        <div class="btn-group btn-group-lg">
                            <button
                                type="button"
                                @click="amountNumpad(4)"
                                class="btn-numpad"
                            >
                                4
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad(5)"
                                class="btn-numpad"
                            >
                                5
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad(6)"
                                class="btn-numpad"
                            >
                                6
                            </button>
                        </div>
                        <div class="btn-group btn-group-lg">
                            <button
                                type="button"
                                @click="amountNumpad(7)"
                                class="btn-numpad"
                            >
                                7
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad(8)"
                                class="btn-numpad"
                            >
                                8
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad(9)"
                                class="btn-numpad"
                            >
                                9
                            </button>
                        </div>
                        <div class="btn-group btn-group-lg">
                            <button
                                type="button"
                                @click="amountNumpad('.')"
                                class="btn-numpad"
                            >
                                .
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad(0)"
                                class="btn-numpad"
                            >
                                0
                            </button>
                            <button
                                type="button"
                                @click="amountNumpad('del')"
                                class="btn-numpad-danger"
                            >
                                Del
                            </button>
                        </div>
                        <div
                            class="btn-group btn-group-lg"
                            style="margin-top: 1px"
                        >
                            <Button
                                label="Exact"
                                icon="pi pi-ticket"
                                class="p-button-lg p-button-primary p-4"
                                @click="exactAmount()"
                                :disabled="paymentMethodType == ''"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div
                    v-if="paymentMethodType == 'Manual'"
                    class="content-height"
                >
                    <h5>
                        <i
                            class="pi pi-id-card py-icon-color"
                            aria-hidden="true"
                        ></i>
                        Add Card Payments Manually
                    </h5>
                    <div class="transactions-card-manual">
                        <div class="form-group">
                            <label> Card Type</label>
                            <Dropdown
                                :options="methodList"
                                v-model="cardType"
                                optionLabel="cardName"
                            />
                        </div>
                        <div class="form-group">
                            <label> Account No (Last 4 digits)</label>
                            <InputText
                                placeholder="e.g 3217"
                                v-model.trim="accountNo"
                            />
                        </div>
                    </div>
                    <Button
                        label="Add Card Payment"
                        icon="pi pi-money-bill"
                        @click="addManualAmount"
                        class="p-button-lg p-button-warning p-4"
                    />
                </div>
                <div v-if="paymentMethodType == 'Cash'" class="content-height">
                    <div class="transactions">
                        <h5>
                            <i
                                class="pi pi-money-bill py-icon-color"
                                aria-hidden="true"
                            ></i>
                            Add Tendered Cash
                        </h5>
                    </div>
                    <!-- Add Cash Button -->
                    <div class="col-md-12 p-0">
                        <Button
                            label="Add Cash"
                            icon="pi pi-money-bill"
                            class="p-button-lg p-button-warning p-4"
                            @click="addCashAmount()"
                            :disabled="paymentMethodType == ''"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="content-height">
                    <div class="transactions">
                        <h5 class="">
                            <i
                                class="pi pi-money-bill py-icon-color"
                                aria-hidden="true"
                            ></i>
                            Payment Methods
                        </h5>
                        <table
                            id="customer_payment_method_manual"
                            class="table table-bordered table-striped py-list-collections table-hover"
                        >
                            <tr
                                v-for="(item, index) in paymentList"
                                :key="item"
                            >
                                <td class="text-left">
                                    {{ item.paymentType }}
                                </td>
                                <td class="text-left">
                                    {{ currency }}
                                    <span id="history_total_cash">{{
                                        fixLength(item.transTotalAmount)
                                    }}</span>
                                    <span
                                        @click="deletePayment(index, item)"
                                        class="payment_cross"
                                        ><i class="pi pi-times"></i
                                    ></span>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="background-color: green; color: #fff"
                                >
                                    Total
                                </td>
                                <td
                                    class="text-left"
                                    style="background-color: green; color: #fff"
                                >
                                    {{ currency }}
                                    <b>{{
                                        fixLength(totalPaymentsReceived)
                                    }}</b>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <!-- <div class="col-4">
                            <Button
                                label="Done"
                                icon="pi pi-check-circle"
                                class="p-button-lg p-button-primary p-4"
                                @click="confirmPayments()"
                                :disabled="
                                    (restriction == 'Yes' &&
                                        paymentRounding > 0) ||
                                    (paymentMethodType != 'Pay Later' &&
                                        totalPaymentsReceived <= 0) ||
                                    (paymentMethodType == 'Pay Later' &&
                                        totalPaymentsReceived > 0)
                                "
                            />
                        </div> -->
                        <div class="col">
                            <Button
                                label="Print Receipt & Confirm"
                                icon="pi pi-print"
                                class="p-button-lg p-button-primary p-4"
                                @click="printReceiptAndConfirm()"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Dialog>
    <Dialog
        v-model:visible="paymentCancelDialog"
        :style="{ width: '600px' }"
        header="Confirm"
    >
        <div class="confirmation-content">
            <i
                class="pi pi-exclamation-triangle p-mr-3"
                style="font-size: 2rem"
            />
            <span
                >Are you sure to cancel ? You will lost the payments of amount
                <b> ${{ fixLength(totalPaymentsReceived) }}</b>
            </span>
        </div>
        <template #footer>
            <Button
                label="No"
                icon="pi pi-times"
                class="p-button-text"
                @click="cancelPaymentConfirm"
            />
            <Button
                label="Yes"
                icon="pi pi-check"
                class="p-button-danger"
                @click="closePaymentScreen"
            />
        </template>
    </Dialog>
    <Dialog
        v-model:visible="paymentConfirmDialog"
        :style="{ width: '600px' }"
        header="Confirm"
    >
        <div class="confirmation-content">
            <i
                class="pi pi-exclamation-triangle p-mr-3"
                style="font-size: 2rem"
            />
            <span
                >Are you sure to continue this invoice. The remaining balance
                for this invoice is <b> ${{ fixLength(paymentRounding) }}</b> ?
            </span>
        </div>
        <template #footer>
            <Button
                label="No"
                icon="pi pi-times"
                class="p-button-text"
                @click="paymentConfirmDialog = false"
            />
            <Button
                label="Yes"
                icon="pi pi-check"
                class="p-button-danger"
                @click="emitPayments()"
            />
        </template>
    </Dialog>
</template>

<script lang="ts">
import { Options, mixins } from "vue-class-component";
import Toaster from "../helpers/Toaster";
import PaymentService from "../service/PaymentService";
import UtilityOptions from "../mixins/UtilityOptions";

interface IPaymentMethod {
    bankId: number;
    branchId: number;
    cardCharges: number;
    cardName: string;
    chargeCustomer: string;
    id: string;
}

interface PaymentListType {
    paymentType: string;
    accountNo: string;
    terminalId: string;
    authCode: string;
    transId: string;
    transStatus: string;
    transType: string;
    transDate: string;
    transTime: string;
    transAmount: number;
    transTotalAmount: number;
    transRef: string;
    entryMode: string;
    hostResponse: string;
    giftCardRef: string;
    cardBalance: string;
    tendered: number;
    change: number;
    roundOff: number;
    bankID: number;
}

@Options({
    props: {
        receiptDetail: Object,
    },

    watch: {
        receiptDetail(obj) {
            console.log("objjjj", obj);

            this.paymentDialog = obj.dialogStatus;
            this.closeConfirmation = obj.closeConfirmation;
            this.amountLeft = Number(this.totalBill);
            this.itemSource = obj.itemSource;
            this.itemList = obj.itemList;
            this.netTotal = obj.netTotal;
            this.restriction = obj.restriction;
            this.customerID = obj.customerID;
            this.customerName = obj.customerName;
            this.paymentAction.needlePoints = obj.needlePoints;
            this.dialogTilte =
                obj.dialogTilte + " for Customer " + this.customerName;

            this.employeeName = obj.employeeName;
            this.storeName = obj.storeName;
            this.storeAddress = obj.storeAddress;
        },
        discountAmount(newDiscount) {
            this.addDiscount();
        },
    },
    emits: ["closePaymentScreenEvent", "getProceededPaymentsEvent"],
})
export default class PaymentScreen extends mixins(UtilityOptions) {
    private customerID;
    private methodList: IPaymentMethod[] = [];
    private customerName;
    private accountNo = "";
    private cardType = {
        bankId: 0,
        branchId: 0,
        cardCharges: 0,
        cardName: "",
        chargeCustomer: "",
        id: 0,
    };
    private paymentService;
    private paymentDialog = false;
    private paymentConfirmDialog = false;
    private closeConfirmation = false;
    private paymentCancelDialog = false;
    private itemSource = "";
    private transStatus = "000";
    private toast;
    private restriction = "";
    private screenNumber = "";
    private paymentMethodType = "Cash";
    private amountLeft = 0;
    private discountAmount: number = 0; // Add the discountAmount property here
    private roundedAmt = 0;
    private tipAmountTerminal = 0;
    private paymentAction = {
        tendered: 0,
        needlePoints: 0,
    };
    private itemList = [];
    private netTotal = 0;

    private paymentList: PaymentListType[] = [];

    private employeeName = "";
    private storeName = "";
    private storeAddress = "";

    created() {
        this.paymentService = new PaymentService();
        this.toast = new Toaster();
    }

    mounted() {
        this.loadPaymentMethod();
    }

    get progressBar() {
        return this.store.getters.getProgressBar;
    }

    get totalBill() {
        return this.store.getters.getTotalBill;
    }

    closePaymentScreen() {
        this.paymentList = [];
        this.$emit("closePaymentScreenEvent");
        this.paymentCancelDialog = false;
    }

    cancelPaymentConfirm() {
        this.paymentDialog = true;
        this.paymentCancelDialog = false;
    }

    confirmPaymentCancel() {
        if (this.totalPaymentsReceived > 0) {
            this.paymentCancelDialog = true;
        } else {
            this.paymentList = [];
            this.$emit("closePaymentScreenEvent");
            this.paymentDialog = false;
        }
    }

    amountNumpad(num) {
        num = String(num);

        if (num == "del") {
            this.paymentAction.tendered = 0;
            this.screenNumber = "";
        } else {
            if (this.paymentRounding > 0 || this.paymentMethodType == "Tip") {
                this.screenNumber = this.screenNumber + num;
                this.paymentAction.tendered = Number(this.screenNumber);
            } else {
                this.toast.showWarning(
                    "Invalid Amount must be greater then zero"
                );
            }
        }
    }

    exactAmount() {
        this.paymentAction.tendered = this.paymentRounding;
    }

    fixLength(value) {
        const num = Number(value);
        value = num.toFixed(2);
        return value;
    }

    fixLengthNumber(value) {
        const num = Number(value);
        value = num.toFixed(2);
        value = Number(value);
        return value;
    }

    get totalPaymentsReceived() {
        let total = 0;
        this.paymentList.forEach((e) => {
            if (e.paymentType != "Tip") {
                total = total + e.transTotalAmount;
            }
        });

        return Number(total);
    }

    addDiscount(discountAmount) {
        const total = this.netTotal;
        this.amountLeft = total;
        const discount = this.amountLeft * (this.discountAmount / 100);

        this.paymentRounding;
        this.amountLeft = this.amountLeft - discount;
    }

    addCashAmount() {
        const tendered = Number(this.paymentAction.tendered);
        if (tendered == 0) {
            this.toast.showError("Please enter amount greater then zero");
        } else {
            if (!this.checkCashPayment) {
                const receivableAmount = this.fixLengthNumber(
                    tendered - this.changeAmount
                );
                this.paymentList.push({
                    paymentType: "Cash",
                    accountNo: "",
                    transTotalAmount: receivableAmount,
                    terminalId: "Manual",
                    authCode: "",
                    hostResponse: "",
                    transId: "",
                    transStatus: this.transStatus,
                    transType: this.itemSource,
                    transDate: "",
                    transTime: "",
                    transAmount: receivableAmount,
                    transRef: "",
                    entryMode: "",
                    giftCardRef: "",
                    cardBalance: "",
                    tendered: this.fixLengthNumber(tendered),
                    change: this.fixLengthNumber(this.changeAmount),
                    roundOff: this.fixLengthNumber(this.roundedAmt),
                    bankID: 0,
                });
            } else {
                this.toast.showError("Cash type is already added");
            }
        }
    }

    addManualAmount() {
        if (this.accountNo.length != 4 || this.cardType == null) {
            this.toast.showError(
                "Please choose Card Type and Card No must be 4 digits"
            );
        } else {
            const tendered = Number(this.paymentAction.tendered);
            if (tendered == 0) {
                this.toast.showError("Please enter amount greater then zero");
            } else {
                const receivableAmount = this.fixLengthNumber(
                    tendered - this.changeAmount
                );

                this.paymentList.push({
                    paymentType: this.cardType.cardName,
                    accountNo: this.accountNo,
                    transTotalAmount: receivableAmount,
                    terminalId: "Manual",
                    authCode: "",
                    hostResponse: "",
                    transId: "",
                    transStatus: this.transStatus,
                    transType: this.itemSource,
                    transDate: "",
                    transTime: "",
                    transAmount: receivableAmount,
                    transRef: "",
                    entryMode: "",
                    giftCardRef: "",
                    cardBalance: "",
                    bankID: this.cardType.bankId,
                    tendered: this.fixLengthNumber(tendered),
                    change: this.fixLengthNumber(this.changeAmount),
                    roundOff: 0,
                });
                this.toast.showSuccess(
                    this.cardType.cardName + " Payment added successfully"
                );
                this.accountNo = "";
            }
        }
    }

    get changeAmount() {
        let change = 0;
        const amountLeft = this.paymentRounding;
        const tendered = Number(this.paymentAction.tendered);
        const balance = tendered - amountLeft;

        if (balance > 0) {
            change = balance;
        }

        return change;
    }

    deletePayment(index, obj) {
        this.paymentList.splice(index, 1);
        this.toast.showSuccess(
            "Amount of $" +
                this.fixLength(obj.transTotalAmount) +
                " removed successfully"
        );
    }

    clearAccountNo() {
        this.accountNo = "";
    }

    get checkCashPayment() {
        let status = false;
        this.paymentList.forEach((e) => {
            if (e.paymentType == "Cash") {
                status = true;
            }
        });

        return status;
    }

    get paymentRounding() {
        let amountLeftTemp = 0;
        let amountPaidTemp = 0;

        //REDUCING THE AMOUNT PAID
        this.paymentList.forEach((e) => {
            if (e.paymentType != "Tip") {
                amountPaidTemp = amountPaidTemp + e.transTotalAmount;
            }
        });

        if (this.paymentMethodType == "Cash") {
            const amountLeft = this.amountLeft - amountPaidTemp;
            const roundNum = Math.round(amountLeft / 0.05) * 0.05;
            amountLeftTemp = Number(roundNum);

            if (this.roundedAmt == 0) {
                this.roundedAmt = roundNum - amountLeft;
            }
        } else if (this.paymentMethodType != "Tip") {
            amountLeftTemp = this.amountLeft - amountPaidTemp;
            this.roundedAmt = 0;
        } else {
            //nothing
        }

        this.paymentAction.tendered = 0;
        this.screenNumber = "";

        return amountLeftTemp;
    }

    confirmPayments() {
        //restriction == 'Yes'
        if (this.paymentRounding > 0.2 && this.restriction == "No") {
            console.log("restriction == No");

            this.paymentConfirmDialog = true;
        } else {
            console.log("emitPayments called");

            this.emitPayments();
        }
    }

    printReceipt() {
        // Get the current date and time
        const currentDate = new Date();
        const options = {
            month: "long",
            day: "numeric",
            year: "numeric",
            hour: "numeric",
            minute: "numeric",
            second: "numeric",
            hour12: true, // To display time in 24-hour format
        };
        const formattedDate = currentDate.toLocaleDateString(
            undefined,
            options
        );

        console.log("paymentList", this.paymentList);

        const randomDecimal = Math.random();
        // Multiply the random decimal by 1000000 to get a number between 0 and 999999.999...
        const randomNumber = randomDecimal * 1000000;
        // Use Math.floor() to remove the decimal part and get a 6-digit number
        const invoiceNumber = Math.floor(randomNumber);
        const logoSrc =
            require("@/assets/images/pharmacy-Receipt-logo.png").default;

        // Define the content of the receipt that needs to be printed
        const receiptContent = `<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Tax Invoice - Pharmacy</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                        }

                        /* Invoice container styles */
                        #invoice {
                            width: 8cm;
                            margin: 4cm auto; /* Add spacing from top and bottom */
                            padding: 10px;
                            border: 1px solid #ccc;
                        }

                        /* Header styles */
                        #invoice .header {
                            text-align: center;
                            margin-bottom: 10px;
                        }

                        /* Customer details styles */
                        #invoice .customer-details {
                            margin-bottom: 10px;
                        }

                        /* Item table styles */
                        #invoice .item-table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        #invoice .item-table th, #invoice .item-table td {
                            border: 1px solid #ccc;
                            padding: 5px;
                            text-align: center;
                        }

                        /* Total section styles */
                        #invoice .total {
                            margin-top: 10px;
                            text-align: right;
                        }

                        /* Footer styles */
                        #invoice .footer {
                            margin-top: 20px;
                            text-align: center;
                        }

                        /* Logo styles */
                        #logo {
                            width: 100%;
                            height: 4cm; /* Set the logo height to 4cm */
                            display: block;
                            margin-bottom: 10px;
                        }
                    </style>
                </head>
                <body>
                    <div id="invoice">
                        <img src="${logoSrc}" alt="Pharmacy Logo" id="logo">

                        <div class="header">
                            <h2>Tax Invoice</h2>
                            <p>${this.storeName}</p>
                            <p>${this.storeAddress}</p>
                            <p>Phone: 06 537 9227</p>
                        </div>

                        <div class="customer-details">
                            <p>Date: ${formattedDate}</p>
                            <p>Invoice No.: ${invoiceNumber} </p>
                            <p>Customer: ${this.customerName}</p>
                            <p>Employee: ${this.employeeName}</p>
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
                                ${this.itemList
                                    .map(
                                        (item) => `
                                <tr>
                                    <td>${item.productName}</td>
                                    <td>${item.unit}</td>
                                    <td>${item.sellingPrice} ${this.currency}</td>
                                    <td>${item.subTotal} ${this.currency}</td>
                                </tr>
                                `
                                    )
                                    .join("")}
                                <!-- You can add more products here -->
                            </tbody>
                        </table>

                        <div class="total">
                            <h4>Net Total: ${this.fixLength(
                                this.totalBill
                            )} </h4>
                            ${this.paymentList
                                .map(
                                    (item) =>
                                        `  <p>${item.paymentType} : ${
                                            item.transTotalAmount +
                                            " " +
                                            this.currency
                                        }</p>`
                                )
                                .join("")}
                            <h3>Total: ${this.fixLength(
                                this.totalPaymentsReceived
                            )} ${this.currency}</h3>
                            <p>Discount: ${this.discountAmount}%</p>
                            <p>Balance Due: ${this.fixLength(
                                this.paymentRounding
                            )} ${this.currency}</p>
                        </div>

                        <div class="footer">
                            <p>Thank you for your visit, have a nice day!</p>
                            <br />
                            <p>By: Smart Link</p>
                        </div>
                    </div>
                </body>
                </html>
                    `;

        // Create a new hidden window and write the receipt content into it
        const printWindow = window.open("", "_blank", "width=800,height=auto");
        printWindow.document.open();
        printWindow.document.write(receiptContent);
        printWindow.document.close();

        // Trigger the print dialog for the hidden window
        setTimeout(() => {
            printWindow.print();
        }, 500);
        // Close the hidden window after printing is done
        //printWindow.close();
    }

    printReceiptAndConfirm() {
        this.confirmPayments();

        //this.printReceipt();
    }

    emitPayments() {
        this.$emit("getProceededPaymentsEvent", this.paymentList, this.discountAmount);
        this.paymentDialog = false;
        this.paymentConfirmDialog = false;
        this.paymentList = [];
        this.clearPaymentScreen();
    }

    clearPaymentScreen() {
        this.amountLeft = 0;
        this.discountAmount = 0;
        this.paymentAction.tendered = 0;
        this.paymentAction.needlePoints = 0;
    }

    loadPaymentMethod() {
        this.paymentService.paymentMethods().then((res) => {
            this.methodList = this.camelizeKeys(res.option);
        });
    }

    get currency() {
        return this.store.getters.getCurrency;
    }
}
</script>

<style scoped>
.py-icon-color {
    color: orangered;
}

.pay-size-bx {
    background-color: #fff;
    border: 1px solid #eee;
    margin-right: 5px;
    display: inline-block;
}

.py-span {
    font-size: 16px;
    padding: 5px;
    width: 100%;
    border-radius: 5px;
    color: #000;
    background-color: #f7f7f7;
    margin: 0px;
    text-align: center;
    box-shadow: 0px 0px 5px 2px #ccc;
}

.py-span img {
    border-radius: 5px;
    width: 100%;
    display: block;
}

.py-description {
    border: 1px dotted #ccc;
    box-shadow: 0px 0px 10px 2px #eee;
    border-radius: 5px;
    padding: 2px 2px;
    margin: 1px;
}

.btn-numpad {
    width: 7.5vw;
    height: 10.8vh;
    background-color: #004c97;
    border-radius: 5px;
    font-size: 25px;
    color: #fff;
    border: 1px solid #fff;
}

.btn-numpad-danger {
    width: 7.5vw;
    height: 10.8vh;
    background-color: #c00;
    border-radius: 5px;
    font-size: 25px;
    border: 1px solid #fff;
    color: #fff;
}

.transactions {
    background-color: #fff;
    height: 48vh;
    min-height: 48vh;
    overflow-y: scroll;
}

.transactions-card-manual {
    background-color: #fff;
    height: 43vh;
    min-height: 43vh;
    overflow-y: scroll;
}

.transactions-card-manual td {
    padding: 2px;
}

.transactions td {
    padding: 2px;
}

.payment_cross {
    float: right;
    color: #c00;
}
.payment_cross:hover {
    cursor: pointer;
}

.content-height {
    margin: 8px 0px 15px 0px;
}

.py-customize-bx {
    height: 60px;
    background: transparent;
    margin: 0px;
    padding: 0px;
    font-size: 45px;
    border: none;
}

.py-balance-due {
    color: #c00;
}
</style>
