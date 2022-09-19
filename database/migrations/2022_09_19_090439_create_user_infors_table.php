<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInforsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("phone")->nullable();
            $table->unsignedBigInteger("address")->nullable();
            $table->string("gender")->nullable();
            $table->date("birday")->nullable();
            $table->timestamps();
            $table->foreign("user_id")
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign("address")
                ->references('id')
                ->on('addresses')
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
        Schema::dropIfExists('user_infors');
    }
}
