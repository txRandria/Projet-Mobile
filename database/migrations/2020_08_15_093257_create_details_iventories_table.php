<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsIventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_iventories', function (Blueprint $table) {
            $table->id();
            $table->integer('inventaire');
            $table->integer('arrivage');
            $table->integer('condition')->nullable();
            $table->double('qte')->nullable();
            $table->string('ref');
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
        Schema::dropIfExists('details_iventories');
    }
}
