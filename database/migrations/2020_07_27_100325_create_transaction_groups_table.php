<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->timestamps();

            $table->primary(['group_id','transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_groups');
    }
}
