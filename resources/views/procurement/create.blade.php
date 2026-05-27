<!DOCTYPE html>
<html>
<head>

<title>Tambah Procurement</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

body{
    margin:0;
    font-family:Segoe UI;
    background:url("{{ asset('images/kmi2.jpg') }}") no-repeat center center fixed;
    background-size:cover;
}

/* OVERLAY */
.overlay{
    width:100%;
    min-height:100vh;
    background:rgba(0,0,0,0.45);
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

/* CARD */
.form-card{
    width:100%;
    max-width:430px;
    background:white;
    border-radius:12px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.25);
}

/* TITLE */
.form-title{
    font-size:22px;
    font-weight:bold;
    color:#2f3f52;
    margin-bottom:20px;
    text-align:center;
}

/* LABEL */
.form-label{
    font-size:14px;
    font-weight:600;
    margin-bottom:5px;
}

/* INPUT */
.form-control,
.form-select{
    height:42px;
    border-radius:8px;
    font-size:14px;
}

/* BUTTON */
.btn-save{
    width:100%;
    height:42px;
    border:none;
    border-radius:8px;
    background:#0d6efd;
    color:white;
    font-weight:bold;
}

.btn-save:hover{
    background:#0b5ed7;
}

/* BACK */
.btn-back{
    width:100%;
    margin-top:10px;
    border-radius:8px;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="overlay">

    <div class="form-card">

        <div class="form-title">
            Tambah Data Procurement
        </div>

        <form action="{{ route('procurement.store') }}" method="POST">

            @csrf

            <!-- KODE -->
            <div class="mb-3">

                <label class="form-label">
                    Kode
                </label>

                <input type="text"
                    name="kode"
                    class="form-control"
                    placeholder="Masukkan kode"
                    required>

            </div>

            <!-- BARANG -->
            <div class="mb-3">

                <label class="form-label">
                    Barang
                </label>

                <input type="text"
                    name="barang"
                    class="form-control"
                    placeholder="Masukkan nama barang"
                    required>

            </div>

            <!-- STATUS -->
            <div class="mb-3">

                <label class="form-label">
                    Status
                </label>

                <select name="status" class="form-select" required>

                    <option value="">
                        -- Pilih Status --
                    </option>

                    <option value="Request Purchasing">
                        Request Purchasing
                    </option>

                    <option value="Technical Evaluation">
                        Technical Evaluation
                    </option>

                    <option value="Re-Technical Evaluation">
                        Re-Technical Evaluation
                    </option>

                    <option value="Purchase Order">
                        Purchase Order
                    </option>

                </select>

            </div>

            <!-- TANGGAL -->
            <div class="mb-4">

                <label class="form-label">
                    Tanggal
                </label>

                <input type="date"
                    name="tanggal"
                    class="form-control"
                    required>

            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn-save">
                Simpan Data
            </button>

            <a href="{{ route('dashboard') }}"
                class="btn btn-secondary btn-back">

                Kembali

            </a>

        </form>

    </div>

</div>

</body>
</html>