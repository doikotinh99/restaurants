<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("city");
            $table->unsignedBigInteger("district");
            $table->string("detail");
            $table->timestamps();
            $table->foreign("city")
                ->references('id')
                ->on('address_cities')
                ->onDelete('cascade');
            $table->foreign("district")
                ->references('id')
                ->on('address_districts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
