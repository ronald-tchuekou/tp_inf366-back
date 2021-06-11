<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTraitantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_traitants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('utilisateur_id');
            $table->string('departement');
            $table->string('filiere');
            $table->string('role');
            $table->foreign('utilisateur_id')
                ->references('id')->on('utilisateurs')
                ->onDelete('cascade');
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
        Schema::dropIfExists('service_traitants');
    }
}
