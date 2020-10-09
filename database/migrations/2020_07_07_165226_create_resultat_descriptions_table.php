<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultatDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultat_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('type_description')->nullable();
            $table->string('valeur_description')->nullable();
            $table->string('responsable')->nullable();
            $table->string("type_prod")->nullable();
            $table->string("type_qualite")->nullable();
            $table->integer('descriptionAttachable_id')->unsigned();
            $table->string('descriptionAttachable_type');
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
        Schema::dropIfExists('resultat_descriptions');
    }
}
