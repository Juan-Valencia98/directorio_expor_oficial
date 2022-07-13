<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoRolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_rol', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('id_user');
            $table->integer('id_rol');
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_rol')->references('id_rol')->on('rol');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_rol');
    }
}
