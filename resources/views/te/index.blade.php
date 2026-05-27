<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Technical Evaluation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

body{
    font-family:Segoe UI;
    margin:0;
    background:url("{{ asset('images/kmi2.jpg') }}") no-repeat center center fixed;
    background-size:cover;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#2f3f52;
    position:fixed;
    top:0;
    left:0;
    z-index:100;
    display:flex;
    flex-direction:column;
    color:white;
}

.sidebar-header{
    padding:25px 20px;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

.sidebar-header img{
    width:150px;
}

.sidebar-menu{
    padding-top:10px;
}

.sidebar-menu a{
    display:block;
    padding:15px 22px;
    color:#dce4ec;
    text-decoration:none;
    font-size:15px;
    transition:0.3s;
}

.sidebar-menu a:hover{
    background:#3e5670;
    color:white;
}

.sidebar-menu .active{
    background:#456ea4;
    color:white;
}

/* LOGOUT */
.logout{
    margin-top:auto;
    padding:25px 20px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
}

.logout form{
    width:100%;
    display:flex;
    justify-content:center;
}

.logout button{
    width:180px;
    border:none;
    background:#dc3545;
    color:white;
    padding:12px;
    border-radius:12px;
    font-weight:bold;
    transition:0.3s;
}

.logout button:hover{
    background:#bb2d3b;
    transform:scale(1.03);
}

.admin-text{
    margin-top:12px;
    color:white;
    font-weight:700;
    font-size:16px;
    text-align:center;
}

/* CONTENT */
.content{
    margin-left:240px;
    padding:25px;
}

/* TOPBAR */
.topbar{
    background:#264a67;
    border-radius:45px;
    padding:22px 35px;
    width:100%;
    margin-bottom:10px;
    position:sticky;
    top:10px;
    z-index:9999;
}

.header-title{
    font-size:16px;
    font-weight:700;
    color:white;
}

/* TITLE */
.welcome-text{
    margin-top:20px;
    margin-bottom:25px;
    font-size:30px;
    color:black;
}

/* CARD */
.card-box{
    background:rgba(255,255,255,0.96);
    border-radius:20px;
    padding:25px;
    box-shadow:0 5px 15px rgba(0, 0, 0, 0);
}

/* BUTTON AKSI */

.btn-warning{
    background:#ffc107;
    color:white;
    border:none;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    padding:4px 10px;
}

.btn-danger{
    background:#dc3545;
    color:white;
    border:none;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    padding:4px 10px;
}

.btn-warning:hover{
    background:#e0a800;
    color:white;
}

.btn-danger:hover{
    background:#bb2d3b;
    color:white;
}

/* TABLE */
.table th{
    background:#2f3f52 !important;
    color:white !important;
    text-align:center;
    padding:14px;
}

.table td{
    text-align:center;
    vertical-align:middle;
    padding:14px;
}

/* STATUS */
.status-lolos,
.status-tidak,
.status-pending{
    display:inline-block;
    min-width:120px;
    text-align:center;
    padding:7px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.status-lolos{
    background:#198754;
    color:white;
}

.status-tidak{
    background:#dc3545;
    color:white;
}

.status-pending{
    background:#ffc107;
    color:rgb(255, 255, 255);
}

/* MODAL */
.overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    display:none;
    z-index:200;
}

.modal-form{
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    width:420px;
    background:white;
    border-radius:18px;
    padding:30px;
    z-index:300;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    display:none;
}

.modal-form h3{
    text-align:center;
    font-weight:bold;
    margin-bottom:20px;
}

/* =========================
   MODAL EDIT STYLE BARU
========================= */

.custom-modal{
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    width:420px;
    background:#ffffff;
    border-radius:22px;
    padding:28px;
    z-index:99999;
    box-shadow:0 15px 40px rgba(0,0,0,0.25);
    display:none;
    animation:modalShow 0.3s ease;
}

.custom-modal h2{
    text-align:center;
    font-size:24px;
    font-weight:800;
    margin-bottom:25px;
    color:#222;
}

.custom-modal label{
    font-weight:700;
    margin-bottom:8px;
    color:#333;
    display:block;
}

.custom-modal .form-control,
.custom-modal .form-select{
    height:48px;
    border-radius:10px;
    margin-bottom:18px;
}

.btn-save{
    width:100%;
    background:#0d6efd;
    border:none;
    color:white;
    height:48px;
    border-radius:10px;
    font-weight:700;
    font-size:16px;
    transition:0.3s;
}

.btn-save:hover{
    background:#0b5ed7;
}

.btn-kembali{
    width:100%;
    background:#6c757d;
    border:none;
    color:white;
    height:45px;
    border-radius:8px;
    font-weight:600;
    margin-top:12px;
}

.btn-kembali:hover{
    background:#5c636a;
}

/* =========================
   MODAL HAPUS STYLE
========================= */

.delete-modal{
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    width:390px;
    background:white;
    border-radius:22px;
    padding:35px 30px;
    z-index:999999;
    display:none;
    text-align:center;
    box-shadow:0 15px 40px rgba(0,0,0,0.25);
    animation:modalShow 0.3s ease;
}

.delete-icon{
    width:90px;
    height:90px;
    border:4px solid #ff6b6b;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    margin-bottom:20px;
}

.delete-icon i{
    font-size:45px;
    color:#ff6b6b;
    font-weight:bold;
}

.delete-title{
    font-size:20px;
    font-weight:800;
    color:#333;
    margin-bottom:10px;
}

.delete-text{
    color:#777;
    font-size:15px;
    margin-bottom:25px;
}

.delete-buttons{
    display:flex;
    justify-content:center;
    gap:12px;
}

.btn-batal{
    background:#0d47a1;
    color:white;
    border:none;
    padding:10px 25px;
    border-radius:10px;
    font-weight:700;
}

.btn-hapus{
    background:#dc3545;
    color:white;
    border:none;
    padding:10px 25px;
    border-radius:10px;
    font-weight:700;
}

.overlay-dark{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    z-index:9999;
    display:none;
}

@keyframes modalShow{
    from{
        opacity:0;
        transform:translate(-50%,-45%);
    }
    to{
        opacity:1;
        transform:translate(-50%,-50%);
    }
}

/* TOAST SUCCESS */
.toast-success{
    position:fixed;
    top:20px;
    left:50%;
    transform:translateX(-50%);
    background:white;
    min-width:320px;
    max-width:450px;
    padding:14px 20px;
    border-radius:14px;
    display:flex;
    align-items:center;
    gap:14px;
    box-shadow:0 8px 25px rgba(0,0,0,0.18);
    z-index:999999;
    border-left:6px solid #22c55e;
    animation: slideDown 0.4s ease, fadeOut 0.5s ease 2.5s forwards;
}

.toast-success i{
    font-size:28px;
    color:#22c55e;
}

.toast-text{
    flex:1;
    font-size:14px;
    font-weight:600;
    color:#333;
}

.close-toast{
    font-size:20px;
    cursor:pointer;
    color:#666;
}

@keyframes slideDown{
    from{
        opacity:0;
        transform:translate(-50%,-40px);
    }
    to{
        opacity:1;
        transform:translate(-50%,0);
    }
}

@keyframes fadeOut{
    to{
        opacity:0;
        transform:translate(-50%,-20px);
    }
}

/* RESPONSIVE */
@media(max-width:768px){

.sidebar{
    width:100%;
    height:auto;
    position:relative;
}

.content{
    margin-left:0;
}

.topbar{
    border-radius:20px;
}

.modal-form,
.custom-modal,
.delete-modal{
    width:95%;
}

}

</style>

</head>

<body>

<!-- TOAST SUCCESS -->
@if(session('success'))
<div class="toast-success" id="successToast">

    <i class="bi bi-check-circle-fill"></i>

    <div class="toast-text">
        {{ session('success') }}
    </div>

    <div class="close-toast" onclick="document.getElementById('successToast').remove()">
        ×
    </div>

</div>
@endif

<!-- SIDEBAR -->
<div class="sidebar">

    <div>

        <div class="sidebar-header">
            <img src="{{ asset('images/kmi-logo.png') }}">
        </div>

        <div class="sidebar-menu">

            <a href="/dashboard">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <a href="{{ route('rp.index') }}">
                <i class="bi bi-file-earmark-text"></i>
                Request Purchasing
            </a>

            <a class="active" href="{{ route('te.index') }}">
                <i class="bi bi-clipboard-check"></i>
                Technical Evaluation
            </a>

            <a href="{{ route('rete.index') }}">
                <i class="bi bi-arrow-repeat"></i>
                Re-Technical Evaluation
            </a>

            <a href="{{ route('po.index') }}">
                <i class="bi bi-bag-check"></i>
                Purchase Order
            </a>

            <a href="#">
                <i class="bi bi-bar-chart-fill"></i>
                Laporan
            </a>

        </div>

    </div>

    <!-- LOGOUT -->
    <div class="logout">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>

        </form>

        <div class="admin-text">
            Admin
        </div>

    </div>

</div>

<!-- CONTENT -->
<div class="content">

    <div class="topbar">
        <div class="header-title">
            PROCUREMENT SYSTEM - PT.KMI
        </div>
    </div>

    <div class="welcome-text">
        <b>TECHNICAL EVALUATION</b> (TE)
    </div>

    <div class="card-box">

        <div class="d-flex justify-content-between mb-3">
            <h3 class="fw-bold m-0">Data Technical Evaluation</h3>

            <button class="btn btn-primary" onclick="openTambahModal()">
                Tambah TE
            </button>
        </div>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode TE</th>
                    <th>Vendor</th>
                    <th>Hasil Evaluasi</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($te as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->kode_te }}</td>

                    <td>{{ $item->vendor }}</td>

                    <td>{{ $item->hasil_evaluasi }}</td>

                    <td>

                        @if($item->status == 'Lolos')

                            <span class="status-lolos">
                                {{ $item->status }}
                            </span>

                        @elseif($item->status == 'Tidak Lolos')

                            <span class="status-tidak">
                                {{ $item->status }}
                            </span>

                        @else

                            <span class="status-pending">
                                {{ $item->status }}
                            </span>

                        @endif

                    </td>

                    <td>{{ $item->tanggal }}</td>

                    <td>

                        <button class="btn btn-warning"

                        onclick="openEditModal(
                            '{{ $item->id }}',
                            '{{ $item->kode_te }}',
                            '{{ $item->vendor }}',
                            '{{ $item->hasil_evaluasi }}',
                            '{{ $item->status }}',
                            '{{ $item->tanggal }}'
                        )">

                            Edit

                        </button>

                        <button class="btn btn-danger"

                        onclick="openDeleteModal(
                            '{{ route('te.delete', $item->id) }}'
                        )">

                            Hapus

                        </button>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7">Data belum ada</td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL TAMBAH -->
<div class="overlay" id="tambahOverlay"></div>

<div class="modal-form" id="tambahModal">

    <h3>Tambah Technical Evaluation</h3>

    <form action="{{ route('te.store') }}" method="POST">

        @csrf

        <label class="fw-bold mb-2">Kode TE</label>
        <input type="text" name="kode_te" class="form-control mb-3" required>

        <label class="fw-bold mb-2">Vendor</label>
        <input type="text" name="vendor" class="form-control mb-3" required>

        <label class="fw-bold mb-2">Hasil Evaluasi</label>
        <input type="text" name="hasil_evaluasi" class="form-control mb-3" required>

        <label class="fw-bold mb-2">Status</label>

        <select name="status" class="form-select mb-3" required>

            <option value="">-- Pilih Status --</option>
            <option value="Lolos">Lolos</option>
            <option value="Tidak Lolos">Tidak Lolos</option>
            <option value="Pending">Pending</option>

        </select>

        <label class="fw-bold mb-2">Tanggal</label>
        <input type="date" name="tanggal" class="form-control mb-3" required>

        <button type="submit" class="btn btn-primary w-100">
            Simpan TE
        </button>

        <button type="button"
        class="btn btn-secondary w-100 mt-2"
        onclick="closeTambahModal()">

            Kembali

        </button>

    </form>

</div>

<!-- OVERLAY -->
<div class="overlay-dark" id="overlayDark"></div>

<!-- MODAL EDIT -->
<div class="custom-modal" id="editModal">

    <h2>Edit Technical Evaluation</h2>

    <form id="editForm" method="POST">

        @csrf

        <label>Kode TE</label>
        <input type="text" name="kode_te" id="edit_kode_te" class="form-control" required>

        <label>Vendor</label>
        <input type="text" name="vendor" id="edit_vendor" class="form-control" required>

        <label>Hasil Evaluasi</label>
        <input type="text" name="hasil_evaluasi" id="edit_hasil" class="form-control" required>

        <label>Status</label>

        <select name="status" id="edit_status" class="form-select">

            <option value="">-- Pilih Status --</option>
            <option value="Lolos">Lolos</option>
            <option value="Tidak Lolos">Tidak Lolos</option>
            <option value="Pending">Pending</option>

        </select>

        <label>Tanggal</label>
        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>

        <button type="submit" class="btn-save">
            Simpan TE
        </button>

        <button type="button"
        class="btn-kembali"
        onclick="closeEditModal()">

            Kembali

        </button>

    </form>

</div>

<!-- MODAL DELETE -->
<div class="delete-modal" id="deleteModal">

    <div class="delete-icon">
        <i class="bi bi-exclamation-lg"></i>
    </div>

    <div class="delete-title">
        Yakin Hapus Data Ini?
    </div>

    <div class="delete-text">
        Data yang dihapus tidak dapat dikembalikan lagi
    </div>

    <div class="delete-buttons">

        <button class="btn-batal"
        onclick="closeDeleteModal()">

            Batal

        </button>

        <form id="deleteForm" method="POST">

            @csrf
            @method('DELETE')

            <button type="submit" class="btn-hapus">
                Hapus
            </button>

        </form>

    </div>

</div>

<script>

/* MODAL TAMBAH */

function openTambahModal(){

    document.getElementById('tambahModal').style.display='block';
    document.getElementById('tambahOverlay').style.display='block';

}

function closeTambahModal(){

    document.getElementById('tambahModal').style.display='none';
    document.getElementById('tambahOverlay').style.display='none';

}

/* MODAL EDIT */

function openEditModal(id, kode, vendor, hasil, status, tanggal){

    document.getElementById('editModal').style.display='block';
    document.getElementById('overlayDark').style.display='block';

    document.getElementById('edit_kode_te').value = kode;
    document.getElementById('edit_vendor').value = vendor;
    document.getElementById('edit_hasil').value = hasil;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_tanggal').value = tanggal;

    document.getElementById('editForm').action = "/te/update/" + id;

}

function closeEditModal(){

    document.getElementById('editModal').style.display='none';
    document.getElementById('overlayDark').style.display='none';

}

/* MODAL DELETE */

function openDeleteModal(action){

    document.getElementById('deleteModal').style.display='block';
    document.getElementById('overlayDark').style.display='block';

    document.getElementById('deleteForm').action = action;

}

function closeDeleteModal(){

    document.getElementById('deleteModal').style.display='none';
    document.getElementById('overlayDark').style.display='none';

}

/* AUTO HIDE TOAST */

setTimeout(() => {

    let toast = document.getElementById('successToast');

    if(toast){

        toast.remove();

    }

}, 3000);

</script>

</body>
</html>