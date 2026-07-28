<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // Nombre: 20 caracteres, obligatorio
            $table->string('name', 20);

            // Apellido paterno: 20 caracteres, obligatorio
            $table->string('paternal_surname', 20);

            // Apellido materno: 20 caracteres, puede quedar vacío
            $table->string('maternal_surname', 20)->nullable();

            // CURP: 18 caracteres, obligatorio
            $table->string('curp', 18);

            // RFC: 13 caracteres, obligatorio
            $table->string('rfc', 13);

            // Género: 6 caracteres, obligatorio
            $table->enum('gender', [
                'Hombre',
                'Mujer',
            ]);

            // Código presupuestal: 23 caracteres, obligatorio
            $table->string('budget_code', 23);

            // Función: no obligatoria, máximo 17 caracteres, con valores específicos
            $table->enum('funcion', [
                'Docente',
                'Administrativo',
                'Docente con grupo',
                'Director'
            ])->nullable();

            // Teléfono: numérico, máximo 10 dígitos
            $table->unsignedBigInteger('telephone');

            // Motivo: numérico, máximo 2 dígitos, puede quedar vacío
            $table->unsignedSmallInteger('reason')->nullable();

            // Fecha de ingreso a la SEP: no obligatorio
            $table->date('date_of_entry_into_the_sep')->nullable();

            // Perfil de estudios: no obligatoria, máximo 27 caracteres, con valores específicos
            $table->enum('study_profile', [
                'Titulado de U.P.N.',
                'Pasante de normal superior',
                'Pasante de maestría',
                'Pasante de U.P.N.'
            ])->nullable();

            // Lengua: no obligatoria, máximo 17 caracteres, con valores específicos
            $table->enum('language', [
                'Mixteca',
                'Cañada',
                'Costa',
                'Istmo',
                'Papaloapan',
                'Sierra sur',
                'Sierra norte',
                'Valles centrales'
            ])->nullable();

            // Variante de lengua: no obligatoria, máximo 4 caracteres, con valores específicos
            $table->enum('language_variant', [
                'Alta',
                'Baja'
            ])->nullable();

            // Llave foránea hacia schools
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools');

            $table->foreignId('human_id')->constrained('humans')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
};
