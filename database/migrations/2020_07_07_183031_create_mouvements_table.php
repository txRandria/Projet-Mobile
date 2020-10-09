<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMouvementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->integer('arrivageAttachable_id')->nullable();
            $table->string('arrivageAttachable_type')->nullable();
            $table->integer('lotAttachable_id')->nullable();
            //$table->string('lotAttachable_type')->nullable();
            $table->integer('localAttachable_id')->nullable();
           // $table->string('localAttachable_type')->nullable();
            $table->integer('siteAttachable_id')->nullable();
           // $table->string('siteAttachable_type')->nullable();
            $table->integer('qualiteAttachable_id')->nullable();
           // $table->string('frsAttachable_type')->nullable();
            $table->integer('grpFrsAttachable_id')->nullable();
            //$table->string('grpFrsAttachable_type')->nullable();
            $table->integer('produitAttachable_id')->nullable();
            //$table->string('produitAttachable_type')->nullable();
            $table->integer('categorieAttachable_id')->nullable();
            //$table->string('categorieAttachable_type')->nullable();
            $table->string('description_mvt')->nullable();
            $table->double('input')->nullable();
            $table->double('output')->nullable();
            $table->double('solde')->nullable();
            $table->string('responsable')->nullable();
            $table->date('date_op')->nullable;
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
        Schema::dropIfExists('mouvements');
    }
}
