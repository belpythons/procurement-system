<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Purchase Order (PO)</title>

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

/* BUTTON TAMBAH */
.btn-primary{
    background:#0d6efd;
    border:none;
    border-radius:10px;
    padding:12px 22px;
    font-weight:bold;
}

.btn-primary:hover{
    background:#0b5ed7;
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
.status-dibuat,
.status-diproses,
.status-selesai{
    display:inline-block;
    min-width:120px;
    text-align:center;
    padding:7px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.status-dibuat{
    background:#0d6efd;
    color:white;
}

.status-diproses{
    background:#ffc107;
    color:white;
}

.status-selesai{
    background:#198754;
    color:white;
}

/* OVERLAY */
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

/* MODAL */
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

/* BUTTON MODAL */
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

/* DELETE MODAL */
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

/* TOAST */
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

.custom-modal,
.delete-modal{
    width:95%;
}

}

</style>

</head>

<body>

<!-- TOAST -->
@if(session('success'))
<div class="toast-success" id="successToast">

    <i class="bi bi-check-circle-fill"></i>

    <div class="toast-text">
        {{ session('success') }}
    </div>

    <div class="close-toast"
         onclick="document.getElementById('successToast').remove()">
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

            <a href="{{ route('te.index') }}">
                <i class="bi bi-clipboard-check"></i>
                Technical Evaluation
            </a>

            <a href="{{ route('rete.index') }}">
                <i class="bi bi-arrow-repeat"></i>
                Re-Technical Evaluation
            </a>

            <a class="active" href="{{ route('po.index') }}">
                <i class="bi bi-bag-check"></i>
                Purchase Order
            </a>

            <a href="{{ route('report.index') }}">
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
        <b>PURCHASE ORDER</b> (PO)
    </div>

    <div class="card-box">

        <div class="d-flex justify-content-between mb-3">

            <h3 class="fw-bold m-0">
                Data Purchase Order
            </h3>

            <button class="btn btn-primary"
                    onclick="openTambahModal()">

                Tambah PO

            </button>

        </div>

        <!-- TABLE -->
        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Kode PO</th>
                    <th>Nama Barang</th>
                    <th>Vendor</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

            @if(isset($po) && $po->count() > 0)

            @foreach($po as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->kode_po }}</td>

                <td>{{ $item->nama_barang }}</td>

                <td>{{ $item->vendor }}</td>

                <td>

                    @if($item->status == 'PO Dibuat')

                        <span class="status-dibuat">
                            {{ $item->status }}
                        </span>

                    @elseif($item->status == 'Diproses')

                        <span class="status-diproses">
                            {{ $item->status }}
                        </span>

                    @else

                        <span class="status-selesai">
                            {{ $item->status }}
                        </span>

                    @endif

                </td>

                <td>{{ $item->tanggal }}</td>

                <td>

                    <button class="btn btn-warning"

                    onclick="openEditModal(
                        '{{ $item->id }}',
                        '{{ $item->kode_po }}',
                        '{{ $item->nama_barang }}',
                        '{{ $item->vendor }}',
                        '{{ $item->status }}',
                        '{{ $item->tanggal }}'
                    )">

                        Edit

                    </button>

                    <button class="btn btn-danger"

                    onclick="openDeleteModal(
                        '{{ route('po.delete', $item->id) }}'
                    )">

                        Hapus

                    </button>

                </td>

            </tr>

            @endforeach

            @else

            <tr>

                <td colspan="7" class="text-center text-muted">
                    Belum ada data Purchase Order
                </td>

            </tr>

            @endif

            </tbody>

        </table>

    </div>

</div>

<!-- OVERLAY -->
<div class="overlay-dark" id="overlayDark"></div>

<!-- MODAL TAMBAH -->
<div class="custom-modal" id="tambahModal">

    <h2>Tambah Purchase Order</h2>

    <form action="{{ route('po.store') }}" method="POST">

        @csrf

        <input type="hidden" name="rp_id" value="1">

        <label>Kode PO</label>
        <input type="text" name="kode_po" class="form-control" required>

        <label>Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" required>

        <label>Vendor</label>
        <input type="text" name="vendor" class="form-control" required>

        <label>Status</label>

        <select name="status" class="form-select" required>

            <option value="">-- Pilih Status --</option>
            <option value="PO Dibuat">PO Dibuat</option>
            <option value="Diproses">Diproses</option>
            <option value="Selesai">Selesai</option>

        </select>

        <label>Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>

        <button type="submit" class="btn-save">
            Simpan PO
        </button>

        <button type="button"
                class="btn-kembali"
                onclick="closeTambahModal()">

            Kembali

        </button>

    </form>

</div>

<!-- MODAL EDIT -->
<div class="custom-modal" id="editModal">

    <h2>Edit Purchase Order</h2>

    <form id="editForm" method="POST">

        @csrf

        <label>Kode PO</label>
        <input type="text" name="kode_po" id="edit_kode_po" class="form-control" required>

        <label>Nama Barang</label>
        <input type="text" name="nama_barang" id="edit_barang" class="form-control" required>

        <label>Vendor</label>
        <input type="text" name="vendor" id="edit_vendor" class="form-control" required>

        <label>Status</label>

        <select name="status" id="edit_status" class="form-select" required>

            <option value="">-- Pilih Status --</option>
            <option value="PO Dibuat">PO Dibuat</option>
            <option value="Diproses">Diproses</option>
            <option value="Selesai">Selesai</option>

        </select>

        <label>Tanggal</label>
        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>

        <button type="submit" class="btn-save">
            Update PO
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
    document.getElementById('overlayDark').style.display='block';

}

function closeTambahModal(){

    document.getElementById('tambahModal').style.display='none';
    document.getElementById('overlayDark').style.display='none';

}

/* MODAL EDIT */

function openEditModal(id, kode, barang, vendor, status, tanggal){

    document.getElementById('editModal').style.display='block';
    document.getElementById('overlayDark').style.display='block';

    document.getElementById('edit_kode_po').value = kode;
    document.getElementById('edit_barang').value = barang;
    document.getElementById('edit_vendor').value = vendor;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_tanggal').value = tanggal;

    document.getElementById('editForm').action = "/po/update/" + id;

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