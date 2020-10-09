<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOriginesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('origines', function (Blueprint $table) {
            $table->id();
            $table->date('debut');
            $table->date('fin');
            $table->string('produit');
            $table->string('qualite');
            $table->string('stock');
            $table->integer('arrivage');
            $table->mediumText('comment')->nullable();
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
        Schema::dropIfExists('origines');
    }
}
