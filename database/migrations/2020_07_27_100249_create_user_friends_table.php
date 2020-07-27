<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_friends', function (Blueprint $table) {
            $table->unsignedBigInteger('who');
            $table->foreign('who')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('with_whom');
            $table->foreign('with_whom')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('accepted')->default(0);
            $table->timestamps();

            $table->primary(['who','with_whom']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_friends');
    }
}
