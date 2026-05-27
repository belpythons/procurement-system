<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah PO</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
font-family:Arial;
}

.card-box{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
margin-top:40px;
}

.header{
background:#6f42c1;
color:white;
padding:20px;
border-radius:10px;
margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container mt-4">

<div class="header">
    <h3>Tambah Purchase Order</h3>
</div>

<div class="card-box">

<form method="POST" action="{{ route('po.store') }}">
@csrf

<div class="mb-3">
    <label>Kode PO</label>
    <input type="text" name="kode_po" class="form-control" required>
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
    <label>Tanggal</label>
    <input type="date" name="tanggal" class="form-control" required>
</div>

<button class="btn btn-success">
    Simpan PO
</button>

<a href="{{ route('po.index') }}" class="btn btn-secondary">
    Kembali
</a>

</form>

</div>

</div>

</body>
</html>