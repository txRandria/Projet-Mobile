<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferts', function (Blueprint $table) {
            $table->id();
            $table->integer('arrivage');
            $table->integer('origineLocal');
            $table->integer('origineSite');
            $table->double('qte');
            $table->integer('siteDest');
            $table->date('validate')->nullable();
            $table->mediumText('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *z
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transferts');
    }
}
