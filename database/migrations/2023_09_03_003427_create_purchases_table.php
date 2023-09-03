<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profileID');
            $table->decimal('discount', 8, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('billNo')->nullable();
            // Add other fields for the purchases table as needed
            $table->timestamps();

            // Define foreign key constraint for profileID
            $table->foreign('profileID')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
