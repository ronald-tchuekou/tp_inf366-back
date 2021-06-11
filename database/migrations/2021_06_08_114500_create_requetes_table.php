<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requetes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_traitant_id')->nullable();
            $table->unsignedBigInteger('service_accueil_id')->nullable();
            $table->unsignedBigInteger('etudiant_id');
            $table->text('objet');
            $table->string('destination');
            $table->text('description');
            $table->text('reponse')->nullable();
            $table->string('statut')->default('Initial');
            $table->timestamp('date_initial');
            $table->timestamp('date_traite')->nullable();
            $table->timestamp('date_rejet')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->timestamp('date_renvoie')->nullable();
            $table->text('motif_renvoie')->nullable();
            $table->foreign('service_traitant_id')->references('id')->on('utilisateurs')->nullOnDelete();
            $table->foreign('service_accueil_id')->references('id')->on('utilisateurs')->nullOnDelete();
            $table->foreign('etudiant_id')->references('id')->on('utilisateurs')->onDelete('cascade');
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
        Schema::dropIfExists('requetes');
    }
}
