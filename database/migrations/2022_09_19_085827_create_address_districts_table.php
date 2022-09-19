<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_districts', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("city_id");
            $table->timestamps();
            $table->foreign("city_id")
                ->references('id')
                ->on('address_cities')
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
        Schema::dropIfExists('address_districts');
    }
}
