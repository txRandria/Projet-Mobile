<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArrivagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrivages', function (Blueprint $table) {
            $table->id();
            $table->date('date_arrive');
            $table->string('responsable')->nullable();
            $table->string('lot');
            $table->string('produit');
            $table->string('qualite');
            $table->integer('stock')->nullable();
            $table->string('fournisseur');
            $table->mediumText('description')->nullable();
            $table->mediumText('observation')->nullable();
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
        Schema::dropIfExists('arrivages');
    }
}
