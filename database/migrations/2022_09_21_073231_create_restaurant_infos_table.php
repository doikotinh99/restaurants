<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // user_id bigint
    // name varchar
    // address bigint
    // time_start time
    // time_end time
    // tables bigint
    public function up()
    {
        Schema::create('restaurant_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("name");
            $table->unsignedBigInteger("address");
            $table->time("time_start")->nullable();
            $table->time("time_end")->nullable();
            $table->unsignedBigInteger("tables");
            $table->timestamps();
            $table->foreign("user_id")
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign("tables")
                ->references('id')
                ->on('table_infos')
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
        Schema::dropIfExists('restaurant_infos');
    }
}
