<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColisTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colis_trans', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->integer('transId');
            $table->integer('invDetails');
            $table->double('poids');
            $table->integer('arrId');
            $table->mediumInteger('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colis_trans');
    }
}
