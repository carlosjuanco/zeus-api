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
        Schema::table('humans', function (Blueprint $table) {
            $table->unsignedBigInteger('churche_id')->nullable()->after('user_id');
            $table->foreign('churche_id')->references('id')->on('churches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('humans', function (Blueprint $table) {
            $table->dropForeign(['churche_id']);
            $table->dropColumn('churche_id');
        });
    }
};
