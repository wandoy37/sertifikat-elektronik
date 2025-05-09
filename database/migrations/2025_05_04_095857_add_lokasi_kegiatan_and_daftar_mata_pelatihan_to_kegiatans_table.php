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
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->text('lokasi_kegiatan')->after('total_jam_kegiatan')->nullable();
            $table->string('daftar_mata_pelatihan')->after('lokasi_kegiatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('lokasi_kegiatan');
            $table->dropColumn('daftar_mata_pelatihan');
        });
    }
};
