<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subjek_pajak', function (Blueprint $table) {
            $table->string('nik', 20)->primary();
            $table->string('nama');
            $table->text('alamat');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('no_hp', 25)->nullable();
            $table->timestamps();
        });

        Schema::create('objek_pajak', function (Blueprint $table) {
            $table->id();
            $table->string('nop', 18)->unique();
            $table->string('nik_pemilik', 20);
            $table->text('letak_objek');
            $table->decimal('luas_bumi', 15, 2)->default(0);
            $table->decimal('luas_bangunan', 15, 2)->default(0);
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('nik_pemilik')->references('nik')->on('subjek_pajak')->cascadeOnDelete();
        });

        Schema::create('sppt', function (Blueprint $table) {
            $table->string('id_sppt', 36)->primary();
            $table->string('nop', 18);
            $table->year('tahun');
            $table->decimal('njop_bumi', 15, 2)->default(0);
            $table->decimal('njop_bangunan', 15, 2)->default(0);
            $table->decimal('pajak_terhutang', 15, 2)->default(0);
            $table->enum('status_bayar', ['piutang', 'lunas'])->default('piutang');
            $table->timestamps();

            $table->foreign('nop')->references('nop')->on('objek_pajak')->cascadeOnDelete();
        });

        Schema::create('mutasi', function (Blueprint $table) {
            $table->string('id_mutasi', 36)->primary();
            $table->string('nop_asal', 18);
            $table->string('nik_lama', 20);
            $table->string('nik_baru', 20);
            $table->enum('jenis_mutasi', ['jual', 'hibah', 'waris', 'lainnya'])->default('lainnya');
            $table->date('tgl_mutasi');
            $table->string('no_arsip')->nullable();
            $table->timestamps();

            $table->foreign('nop_asal')->references('nop')->on('objek_pajak')->cascadeOnDelete();
            $table->foreign('nik_lama')->references('nik')->on('subjek_pajak')->cascadeOnDelete();
            $table->foreign('nik_baru')->references('nik')->on('subjek_pajak')->cascadeOnDelete();
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->string('id_bayar', 36)->primary();
            $table->string('id_sppt', 36);
            $table->date('tgl_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->foreignId('id_petugas')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('id_sppt')->references('id_sppt')->on('sppt')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('mutasi');
        Schema::dropIfExists('sppt');
        Schema::dropIfExists('objek_pajak');
        Schema::dropIfExists('subjek_pajak');
    }
};
