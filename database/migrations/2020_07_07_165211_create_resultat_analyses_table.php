<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultatAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultat_analyses', function (Blueprint $table) {
            $table->id();
            $table->date("date_analyse")->nullable();
            $table->string("type_analyse")->nullable();
            $table->string("details_analyse")->nullable();
            $table->string("valeur_analyse")->nullable();
            $table->string("responsable")->nullable();
            $table->string("type_prod")->nullable();
            $table->string("type_qualite")->nullable();
            $table->integer("resultatAttachable_id")->unsigned();
            $table->string("resultatAttachable_type");
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
        Schema::dropIfExists('resultat_analyses');
    }
}
