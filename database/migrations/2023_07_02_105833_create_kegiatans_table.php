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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kegiatan');
            $table->string('judul_kegiatan');
            $table->string('slug');
            $table->unsignedBigInteger('kategori_id');
            $table->string('tahun_kegiatan');
            $table->string('tanggal_mulai_kegiatan');
            $table->string('tanggal_akhir_kegiatan');
            $table->string('total_jam_kegiatan');
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatans');
    }
};
