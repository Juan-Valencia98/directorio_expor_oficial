<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->integer('id_empresa');
            $table->string('razon_social_empresa');
            $table->text('descripcion_empresa');
            $table->string('nit');
            $table->string('matricula');
            $table->integer('telefono');
            $table->integer('celular_1');
            $table->string('nombre_1');
            $table->integer('celular_2');
            $table->string('nombre_2');
            $table->string('email');
            $table->string('pag_web');
            $table->string('ruex');
            $table->boolean('estado_ruex');
            $table->text('imagen_empresa');
            $table->text('logo_empresa');
            $table->integer('id_estado_empresa');
            $table->enum('estado', ['activo', 'inactivo', 'eliminado'])->default('inactivo');
            $table->timestamps();
            $table->primary('id_empresa');
            $table->foreign('id_estado_empresa')->references('id_estado_empresa')->on('estado_empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
