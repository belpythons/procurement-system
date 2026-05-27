public function up(): void
{
    Schema::create('procurements', function (Blueprint $table) {
        $table->id();

        // 🔥 RELASI BARU (INI YANG BENER)
        $table->foreignId('rp_id')
            ->nullable()
            ->constrained('request_purchasings')
            ->onDelete('cascade');

        $table->string('kode');
        $table->string('barang');
        $table->string('status');
        $table->date('tanggal');

        $table->timestamps();
    });
}