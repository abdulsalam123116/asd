<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrinterCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printer_commands', function (Blueprint $table) {
            $table->id();
            $table->text('data'); // Column for storing data
            $table->enum('printer_type', ['Barcode', 'Receipt']); // ENUM column for printer type
            $table->string('printer_name');
            $table->unsignedBigInteger('branch_id'); // Column for pharmacy ID
            $table->unsignedBigInteger('user_id'); // Column for user ID
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printer_commands');
    }
}
