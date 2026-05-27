<!DOCTYPE html>
<html>
<head>

<title>Tambah Technical Evaluation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
font-family:Arial;
}

.container-box{
padding:40px;
}

.card-box{
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
max-width:700px;
margin:auto;
}

</style>

</head>

<body>

<div class="container-box">

<div class="card-box">

<h3 class="mb-4">
Tambah Technical Evaluation
</h3>

<form method="POST" action="/te/store">

@csrf

<div class="mb-3">

<label>Request Purchasing</label>

<select name="rp_id" class="form-control" required>

<option value="">-- Pilih RP --</option>

@foreach($rp as $data)

<option value="{{ $data->id }}">

{{ $data->kode_rp }} - {{ $data->nama_barang }}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>Vendor</label>

<input
type="text"
name="vendor"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Hasil Evaluasi</label>

<textarea
name="hasil_evaluasi"
class="form-control"
required></textarea>

</div>

<div class="mb-3">

<label>Status</label>

<select name="status" class="form-control">

<option value="Approved">Approved</option>

<option value="Rejected">Rejected</option>

</select>

</div>

<button class="btn btn-success">
Simpan
</button>

<a href="/te" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

</div>

</body>
</html>