<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclEtapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decl_etapes', function (Blueprint $table) {
            $table->id();
            $table->string('lot');
            $table->string('categorie');
            $table->string('state');
            $table->string('date')->nullable();
            $table->integer('arrivage');
            $table->double('qte')->nullable();
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
        Schema::dropIfExists('decl_etapes');
    }
}
