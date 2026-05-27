<!DOCTYPE html>
<html>
<head>

<title>Tambah Re-TE</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
font-family:Arial;
}

.container{
padding:40px;
}

.card{
border:none;
border-radius:15px;
padding:30px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2 class="mb-4">Tambah Re-Technical Evaluation</h2>

<form action="{{ route('rete.store') }}" method="POST">

@csrf

<div class="mb-3">
<label>Kode Re-TE</label>
<input type="text" name="kode_rete" class="form-control" required>
</div>

<div class="mb-3">
<label>Nama Barang</label>
<input type="text" name="nama_barang" class="form-control" required>
</div>

<div class="mb-3">
<label>Vendor</label>
<input type="text" name="vendor" class="form-control" required>
</div>

<div class="mb-3">
<label>Catatan</label>
<textarea name="catatan" class="form-control" required></textarea>
</div>

<button class="btn btn-warning">
Simpan
</button>

<a href="{{ route('rete.index') }}" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

</div>

</body>
</html>