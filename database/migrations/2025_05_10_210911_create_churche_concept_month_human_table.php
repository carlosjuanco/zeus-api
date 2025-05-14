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
        Schema::create('churche_concept_month_human', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_id')
                ->constrained('concepts')
                ->onDelete('cascade');
            $table->foreignId('churche_id')
                ->constrained('churches')
                ->onDelete('cascade');
            $table->foreignId('month_id')
                ->constrained('months')
                ->onDelete('cascade');
            $table->foreignId('human_id')
                ->constrained('humans')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('week'); // 1-4
            $table->unsignedSmallInteger('value'); // 1 - 1000
            $table->unsignedSmallInteger('accumulated'); // 1 - 1000
            $table->string('status', 7);
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
        Schema::dropIfExists('churche_concept');
    }
};
