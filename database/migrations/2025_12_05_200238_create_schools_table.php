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
         Schema::create('schools', function (Blueprint $table) {
            $table->id();

            // Nombre de la escuela, máximo 26 caracteres, puede quedar vacío
            $table->string('name', 26)->nullable();

            // Clave de la escuela, máximo 10 caracteres, puede quedar vacío
            $table->string('key', 10)->nullable();

            // Tipo de escuela, máximo 19 caracteres, obligatorio
            // Puedes usar enum para asegurar los valores permitidos
            $table->enum('type_of_school', [
                'Primaria',
                'Preescolar',
                'Inicial',
                'Albergues escolares'
            ]);

            // Llave foránea hacia communities
            $table->unsignedBigInteger('community_id');
            $table->foreign('community_id')->references('id')->on('communities');

            // Numero progresivo: numérico, 1 dígito, puede quedar vacío
            $table->unsignedTinyInteger('secondary_number')->nullable();
            
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
        Schema::dropIfExists('schools');
    }
};
