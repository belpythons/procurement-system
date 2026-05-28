<!DOCTYPE html>
<html>
<head>

<title>Tambah Procurement</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: url("{{ asset('images/kmi2.jpg') }}") no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
}

/* OVERLAY */
.overlay {
    width: 100%;
    min-height: 100vh;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
}

/* CARD */
.form-card {
    width: 100%;
    max-width: 580px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(8px);
    border-radius: 18px;
    padding: 35px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* TITLE */
.form-title {
    font-size: 24px;
    font-weight: 700;
    color: #2f3f52;
    margin-bottom: 25px;
    text-align: center;
    letter-spacing: -0.5px;
}

/* LABEL */
.form-label {
    font-size: 13.5px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #3e5670;
}

/* INPUT */
.form-control {
    height: 44px;
    border-radius: 10px;
    font-size: 14px;
    border: 1px solid #ced4da;
    padding: 10px 15px;
    transition: all 0.2s ease-in-out;
}

.form-control:focus {
    border-color: #456ea4;
    box-shadow: 0 0 0 0.25rem rgba(69, 110, 164, 0.15);
}

input[type="date"].form-control {
    padding: 8px 12px;
}

/* BUTTONS */
.btn-save {
    width: 100%;
    height: 46px;
    border: none;
    border-radius: 10px;
    background: #0d6efd;
    color: white;
    font-weight: bold;
    font-size: 15px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
}

.btn-save:hover {
    background: #0b5ed7;
    transform: translateY(-1px);
}

.btn-save:active {
    transform: translateY(0);
}

.btn-back {
    width: 100%;
    height: 46px;
    margin-top: 12px;
    border-radius: 10px;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-back:hover {
    transform: translateY(-1px);
}

.btn-back:active {
    transform: translateY(0);
}

hr {
    border-color: rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 15px;
    font-weight: 700;
    color: #2f3f52;
    margin-top: 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    color: #456ea4;
}

</style>

</head>

<body>

<div class="overlay">

    <div class="form-card">

        <div class="form-title">
            <i class="bi bi-file-earmark-plus-fill me-1"></i> Tambah Data Procurement
        </div>

        <form action="{{ route('procurement.store') }}" method="POST">

            @csrf

            <!-- KODE PENGADAAN -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        Kode Pengadaan
                    </label>
                    <input type="text"
                        name="kode_pengadaan"
                        class="form-control @error('kode_pengadaan') is-invalid @enderror"
                        placeholder="Masukkan kode pengadaan (Contoh: PRQ-001)"
                        value="{{ old('kode_pengadaan') }}"
                        required>
                    @error('kode_pengadaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- NAMA BARANG & VENDOR -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Nama Barang
                    </label>
                    <input type="text"
                        name="nama_barang"
                        class="form-control"
                        placeholder="Nama barang"
                        value="{{ old('nama_barang') }}"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Vendor
                    </label>
                    <input type="text"
                        name="vendor"
                        class="form-control"
                        placeholder="Nama vendor"
                        value="{{ old('vendor') }}"
                        required>
                </div>
            </div>

            <!-- MANUAL DATES SECTION -->
            <div class="section-title">
                <i class="bi bi-calendar3"></i> Fase Tanggal (Pengisian Manual)
            </div>
            <hr class="mt-0 mb-3">

            <!-- TANGGAL IN & OUT -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Tanggal In (RP)
                    </label>
                    <input type="date"
                        name="tanggal_in"
                        class="form-control"
                        value="{{ old('tanggal_in') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Tanggal Out (RP)
                    </label>
                    <input type="date"
                        name="tanggal_out"
                        class="form-control"
                        value="{{ old('tanggal_out') }}">
                </div>
            </div>

            <!-- TANGGAL TE & RE-TE -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Tanggal TE
                    </label>
                    <input type="date"
                        name="tanggal_te"
                        class="form-control"
                        value="{{ old('tanggal_te') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Tanggal RE-TE
                    </label>
                    <input type="date"
                        name="tanggal_rete"
                        class="form-control"
                        value="{{ old('tanggal_rete') }}">
                </div>
            </div>

            <!-- TANGGAL PO -->
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label class="form-label">
                        Tanggal PO
                    </label>
                    <input type="date"
                        name="tanggal_po"
                        class="form-control"
                        value="{{ old('tanggal_po') }}">
                </div>
            </div>

            <!-- BUTTONS -->
            <button type="submit" class="btn-save">
                <i class="bi bi-save me-1"></i> Simpan Data
            </button>

            <a href="{{ route('dashboard') }}"
                class="btn btn-secondary btn-back">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

        </form>

    </div>

</div>

</body>
</html>