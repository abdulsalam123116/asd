<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->string('mode');
            $table->integer('stockID');
            $table->string('productName');
            $table->string('generic');
            $table->text('itemDescription')->nullable();
            $table->integer('unit');
            $table->integer('totalUnit');
            $table->integer('stockQty');
            $table->integer('freeUnit');
            $table->integer('supplierBonus');
            $table->string('batchNo');
            $table->integer('packSize');
            $table->integer('sheetSize');
            $table->decimal('purchasePrice', 8, 2);
            $table->decimal('orginalSPrice', 8, 2);
            $table->decimal('sellingPrice', 8, 2);
            $table->decimal('mrp', 8, 2);
            $table->string('brandName');
            $table->string('sectorName');
            $table->string('categoryName');
            $table->string('productType');
            $table->date('expiryDate');
            $table->decimal('cusDisc', 5, 2);
            $table->decimal('purchaseAfterDisc', 8, 2);
            $table->decimal('itemDisc', 8, 2);
            $table->decimal('tax1', 5, 2);
            $table->decimal('tax2', 5, 2);
            $table->decimal('tax3', 5, 2);
            $table->decimal('subTotal', 8, 2);
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_items');
    }
}
