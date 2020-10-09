<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->integer('achatAttachable_id');
            $table->string('achatAttachable_type');
            $table->date('daty')->nullable();
            $table->string('commercial')->nullable();
            $table->string('fournisseur');
            $table->string('groupeFrs');
            $table->double('prix');
            $table->double('taxe');
            $table->string('commentAchat')->nullable();
            $table->string('commentLivraison')->nullable();
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
        Schema::dropIfExists('achats');
    }
}
