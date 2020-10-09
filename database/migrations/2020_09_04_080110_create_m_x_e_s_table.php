<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMXESTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_x_e_s', function (Blueprint $table) {
            $table->id();
            $table->date('date_op');
            $table->integer('arrId');
            $table->integer('lotId')->nullable();
            $table->integer('localId')->nullable();
            $table->integer('siteId')->nullable();
            $table->integer('qualiteId')->nullable();
            $table->integer('assocId')->nullable();
            $table->integer('produitId')->nullable();
            $table->integer('categorieId')->nullable();
            $table->string('description_mvt')->nullable();
            $table->double('input')->nullable();
            $table->double('output')->nullable();
            $table->string('responsable')->nullable();
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
        Schema::dropIfExists('m_x_e_s');
    }
}
