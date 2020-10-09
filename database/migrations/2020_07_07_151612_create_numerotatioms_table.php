<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNumerotatiomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numerotatioms', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique;
            $table->string("categorie");
            $table->mediumText("description")->nullable;
            $table->mediumText("comment")->nullable;
            $table->string("user")->nullable;
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
        Schema::dropIfExists('numerotatioms');
    }
}
