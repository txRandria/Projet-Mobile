<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCumulVarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cumul_vars', function (Blueprint $table) {
            $table->id();
            $table->date('date_op');
            $table->integer('lotId');
            $table->integer('ArrId');
            $table->string('descript');
            $table->double('in');
            $table->double('out');
            $table->integer('siteId');
            $table->integer('motif');
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
        Schema::dropIfExists('cumul_vars');
    }
}
