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
        Schema::table('penandatangans', function (Blueprint $table) {
            $table->string('tanda_tangan_stempel')->nullable()->after('pangkat_golongan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penandatangans', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan_stempel');
        });
    }
};
