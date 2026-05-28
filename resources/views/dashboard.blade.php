<!DOCTYPE html>
<html>
<head>

<title>Dashboard Procurement System</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

body{
    font-family:Segoe UI;
    margin:0;
    background:url("{{ asset('images/kmi2.jpg') }}") no-repeat center center fixed;
    background-size:cover;
}

/* OVERLAY */
.overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    z-index:200;
}

/* MODAL TAMBAH DATA */
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
}

.modal-form h3{
    text-align:center;
    font-weight:bold;
    margin-bottom:25px;
    color:#24364b;
}

.modal-form label{
    font-weight:600;
    margin-bottom:6px;
    color:#222;
}

.modal-form .form-control,
.modal-form .form-select{
    height:45px;
    border-radius:10px;
    margin-bottom:15px;
}

.btn-save{
    width:100%;
    background:#0d6efd;
    color:white;
    border:none;
    border-radius:10px;
    padding:12px;
    font-weight:bold;
    margin-top:10px;
}

.btn-save:hover{
    background:#0b5ed7;
}

.btn-close2{
    width:100%;
    background:#6c757d;
    color:white;
    border:none;
    border-radius:10px;
    padding:12px;
    font-weight:bold;
    margin-top:10px;
    text-decoration:none;
    display:block;
    text-align:center;
}

.btn-close2:hover{
    background:#5c636a;
    color:white;
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
    color:#ffffff;
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

/* TITLE */
.page-title{
    color:rgb(0, 0, 0);
    margin-bottom:20px;
}

.page-title p{
    margin:0;
    opacity:0.9;
}

/* CARD AREA */
.card-wrapper{
    display:grid;
    grid-template-columns:repeat(5, 1fr);
    gap:15px;
}

/* CARD */
.card-stat{
    border-radius:18px;
    color:white;
    padding:15px;
    display:flex;
    align-items:center;
    gap:12px;
    transition:0.3s;
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
    cursor:pointer;
    min-height:95px;
}

.card-stat:hover{
    transform:translateY(-5px) scale(1.02);
}

.card-stat i{
    font-size:40px;
}

.card-stat h4{
    margin:0;
    font-size:22px;
    font-weight:bold;
}

.card-stat div div{
    font-size:23px;
    font-weight:800;
}

.blue{ background:#214da0; }
.green{ background:#3fb92f; }
.orange{ background:#ff961f; }
.purple{ background:#ff0095; }
.dark{ background:#1f2937; }

/* TABLE BOX */
.card-box{
    background:rgba(255,255,255,0.96);
    backdrop-filter:blur(6px);
    border-radius:20px;
    padding:25px;
    margin-top:30px;
    box-shadow:0 5px 15px rgba(0,0,0,0.10);
}

/* SEARCH */
.search-box{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    flex-wrap:wrap;
    margin-bottom:20px;
}

.search-title{
    font-size:26px;
    font-weight:bold;
    color:#2f3f52;
}

.search-input{
    width:300px;
    height:45px;
    border-radius:10px;
}

.btn-add{
    background:#0d6efd;
    color:white;
    border:none;
    border-radius:10px;
    padding:10px 18px;
    font-weight:bold;
    text-decoration:none;
}

.btn-add:hover{
    background:#0b5ed7;
    color:white;
}

/* TABLE */
.table th{
    background:#2f3f52;
    color:white;
    text-align:center;
    padding:14px;
    font-size:18px;
}

.table td{
    vertical-align:middle;
    padding:14px;
    font-size:18px;
    text-align:center;
}

.table td .badge{
    display:inline-flex;
    justify-content:center;
    align-items:center;
    width:190px;
    height:32px;
    font-size:12px;
    font-weight:600;
    border-radius:8px;
    white-space:nowrap;
    padding:0;
    text-align:center;
}

/* BUTTON */
.btn-edit{
    background:#ffc107;
    color:#ffffff;
    border:none;
    padding:3px 10px;
    border-radius:6px;
    font-weight:600;
    font-size:12px;
    display:inline-flex;
    align-items:center;
    gap:4px;
    height:28px;
    text-decoration:none;
}

.btn-delete{
    background:#dc3545;
    color:white;
    border:none;
    padding:3px 10px;
    border-radius:6px;
    font-weight:600;
    font-size:12px;
    display:inline-flex;
    align-items:center;
    gap:4px;
    height:28px;
}

/* DELETE MODAL */
.delete-modal-overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:99999;
}

.delete-modal-box{
    width:420px;
    background:white;
    border-radius:20px;
    padding:35px 30px;
    text-align:center;
    animation:popupScale 0.25s ease;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
}

.delete-icon{
    width:80px;
    height:80px;
    border-radius:50%;
    border:4px solid #ff6b6b;
    color:#ff6b6b;
    font-size:42px;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    margin-bottom:20px;
}

.delete-title{
    font-size:20px;
    font-weight:700;
    color:#333;
    margin-bottom:10px;
}

.delete-subtitle{
    font-size:14px;
    color:#666;
    margin-bottom:25px;
}

.delete-button-group{
    display:flex;
    justify-content:center;
    gap:12px;
}

.btn-batal{
    background:#214da0;
    color:white;
    border:none;
    padding:10px 24px;
    border-radius:8px;
    font-weight:600;
}

.btn-batal:hover{
    background:#183b7c;
}

.btn-hapus{
    background:#dc3545;
    color:white;
    border:none;
    padding:10px 24px;
    border-radius:8px;
    font-weight:600;
}

.btn-hapus:hover{
    background:#bb2d3b;
}

@keyframes popupScale{
    from{
        transform:scale(0.7);
        opacity:0;
    }
    to{
        transform:scale(1);
        opacity:1;
    }
}

/* ALERT SUCCESS */
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
    z-index:9999;
    border-left:6px solid #22c55e;

    animation:
    slideDown 0.4s ease,
    fadeOut 0.5s ease 2.5s forwards;
}

.toast-success i{
    font-size:28px;
    color:#22c55e;
}

.toast-success .toast-text{
    flex:1;
    font-size:14px;
    font-weight:600;
    color:#333;
}

.toast-success .close-toast{
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
@media(max-width:1200px){

.card-wrapper{
    grid-template-columns:repeat(3, 1fr);
}

}

@media(max-width:768px){

.sidebar{
    width:100%;
    height:auto;
    position:relative;
}

.content{
    margin-left:0;
    padding:15px;
}

.topbar{
    border-radius:18px;
}

.search-input{
    width:100%;
}

.modal-form{
    width:95%;
}

.card-wrapper{
    grid-template-columns:repeat(1, 1fr);
}

}

</style>

</head>

<body>

<!-- ALERT SUCCESS -->
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

            <a class="active" href="/dashboard">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <a href="/dashboard?status=RP">
                <i class="bi bi-file-earmark-text"></i>
                Request Purchasing
            </a>

            <a href="/dashboard?status=TE">
                <i class="bi bi-clipboard-check"></i>
                Technical Evaluation
            </a>

            <a href="/dashboard?status=RE-TE">
                <i class="bi bi-arrow-repeat"></i>
                Re-Technical Evaluation
            </a>

            <a href="/dashboard?status=PO">
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
        <b>Selamat Datang</b>, Admin
    </div>


    <!-- CARD -->
    <div class="card-wrapper">

        <div class="card-stat dark"
             onclick="showAllData()">

            <i class="bi bi-database-fill"></i>

            <div>
                <div>Semua Data</div>
                <h4>{{ $totalRP + $totalTE + $totalRETE + $totalPO }}</h4>
            </div>

        </div>

        <div class="card-stat blue"
             onclick="filterStatusExact('RP')">

            <i class="bi bi-file-earmark-text"></i>

            <div>
                <div>Total RP</div>
                <h4>{{ $totalRP }}</h4>
            </div>

        </div>

        <div class="card-stat green"
             onclick="filterStatusExact('TE')">

            <i class="bi bi-clipboard-check"></i>

            <div>
                <div>Total TE</div>
                <h4>{{ $totalTE }}</h4>
            </div>

        </div>

        <div class="card-stat orange"
             onclick="filterStatusExact('RE-TE')">

            <i class="bi bi-arrow-repeat"></i>

            <div>
                <div>Total Re-TE</div>
                <h4>{{ $totalRETE }}</h4>
            </div>

        </div>

        <div class="card-stat purple"
             onclick="filterStatusExact('PO')">

            <i class="bi bi-bag-check"></i>

            <div>
                <div>Total PO</div>
                <h4>{{ $totalPO }}</h4>
            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card-box">

        <div class="search-box">

            <div class="search-title">
                Data Procurement
            </div>

            <div class="d-flex gap-2 flex-wrap">

                <form method="GET"
                      action="{{ route('dashboard') }}"
                      class="d-flex gap-2">

                    <input type="text"
                           id="searchInput"
                           class="form-control search-input"
                           placeholder="Cari kode / barang / status...">

                    <button class="btn btn-primary">
                        Cari
                    </button>

                </form>

            </div>

        </div>

        <table class="table table-bordered table-hover"
               id="dataTable">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Kode Pengadaan</th>
                    <th>Nama Barang</th>
                    <th>Vendor</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

            @php $no = 1; @endphp

            @forelse($procurements as $item)

            <tr data-status="{{ is_object($item) ? $item->status : $item['status'] }}">

                <td>{{ $no++ }}</td>

                <td>
                    {{ is_object($item) ? $item->kode_pengadaan : $item['kode_pengadaan'] }}
                </td>

                <td>
                    {{ is_object($item) ? $item->nama_barang : $item['nama_barang'] }}
                </td>

                <td>
                    {{ is_object($item) ? $item->vendor : $item['vendor'] }}
                </td>

                <td>

                    @php
                        $status = is_object($item)
                            ? $item->status
                            : $item['status'];
                    @endphp

                    @if($status == 'RP')

                        <span class="badge bg-primary">
                            RP
                        </span>

                    @elseif($status == 'TE')

                        <span class="badge bg-success">
                            TE
                        </span>

                    @elseif($status == 'RE-TE')

                        <span class="badge bg-warning text-white">
                            RE-TE
                        </span>

                    @elseif($status == 'PO')

                        <span class="badge"
                              style="background:#ff0095;color:white;">

                            PO

                        </span>

                    @else

                        <span class="badge bg-secondary">
                            {{ $status }}
                        </span>

                    @endif

                </td>

                <td>
                    {{ is_object($item) ? $item->tanggal : $item['tanggal'] }}
                </td>

                <td>

                    <button class="btn-edit"
                            onclick="openEditModal(
                            '{{ is_object($item) ? $item->id : $item['id'] }}',
                            '{{ is_object($item) ? $item->kode_pengadaan : $item['kode_pengadaan'] }}',
                            '{{ is_object($item) ? $item->nama_barang : $item['nama_barang'] }}',
                            '{{ is_object($item) ? $item->vendor : $item['vendor'] }}'
                            )">
                        Edit
                    </button>

                    <form action="{{ route('procurement.delete', is_object($item) ? $item->id : $item['id']) }}"
                        method="POST"
                        class="deleteForm"
                        style="display:inline-block;">

                        @csrf

                        <button type="button"
                                class="btn-delete"
                                onclick="openDeleteModal(this)">
                            Hapus
                        </button>

                    </form>

                    <!-- Phase Automation Approval Buttons -->
                    @if($status == 'RP')
                        <form action="{{ route('procurement.approve_phase', is_object($item) ? $item->id : $item['id']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary" style="font-size:12px; height:28px; border-radius:6px; font-weight:600; padding:3px 10px; vertical-align:middle;">
                                Approve to TE
                            </button>
                        </form>
                    @elseif($status == 'TE')
                        <form action="{{ route('procurement.approve_phase', is_object($item) ? $item->id : $item['id']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="target" value="RE-TE">
                            <button type="submit" class="btn btn-sm btn-warning text-white" style="font-size:12px; height:28px; border-radius:6px; font-weight:600; padding:3px 10px; vertical-align:middle;">
                                Approve to RE-TE
                            </button>
                        </form>
                        <form action="{{ route('procurement.approve_phase', is_object($item) ? $item->id : $item['id']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="target" value="PO">
                            <button type="submit" class="btn btn-sm text-white" style="background:#ff0095; font-size:12px; height:28px; border-radius:6px; font-weight:600; padding:3px 10px; vertical-align:middle;">
                                Approve to PO
                            </button>
                        </form>
                    @elseif($status == 'RE-TE')
                        <form action="{{ route('procurement.approve_phase', is_object($item) ? $item->id : $item['id']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm text-white" style="background:#ff0095; font-size:12px; height:28px; border-radius:6px; font-weight:600; padding:3px 10px; vertical-align:middle;">
                                Approve to PO
                            </button>
                        </form>
                    @endif

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="6">
                    Data kosong
                </td>
            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>



<!-- MODAL EDIT DATA -->
<div class="overlay"
     id="editOverlay"
     style="display:none;"></div>

<div class="modal-form"
     id="editModal"
     style="display:none;">

    <h3>Edit Data Procurement</h3>

    <form id="editForm"
          method="POST">

        @csrf
        @method('PUT')

        <label>Kode Pengadaan</label>

        <input type="text"
               name="kode_pengadaan"
               id="editKode"
               class="form-control"
               required>

        <label>Nama Barang</label>

        <input type="text"
               name="nama_barang"
               id="editBarang"
               class="form-control"
               required>

        <label>Vendor</label>

        <input type="text"
               name="vendor"
               id="editVendor"
               class="form-control"
               required>

        <button type="submit"
                class="btn-save">

            Update Data

        </button>

        <button type="button"
                class="btn-close2"
                onclick="closeEditModal()">

            Kembali

        </button>

    </form>

</div>

<!-- MODAL DELETE -->
<div class="delete-modal-overlay" id="deleteModal">

    <div class="delete-modal-box">

        <div class="delete-icon">
            !
        </div>

        <div class="delete-title">
            Yakin Hapus Data Ini?
        </div>

        <div class="delete-subtitle">
            Data yang dihapus tidak dapat dikembalikan lagi
        </div>

        <div class="delete-button-group">

            <button class="btn-batal"
                    onclick="closeDeleteModal()">

                Batal

            </button>

            <button class="btn-hapus"
                    onclick="submitDeleteForm()">

                Hapus

            </button>

        </div>

    </div>

</div>

<script>

/* SEARCH */

document.getElementById("searchInput").addEventListener("keyup", function() {

    let input = this.value.toLowerCase();

    let rows = document.querySelectorAll("#dataTable tbody tr");

    rows.forEach(function(row) {

        let text = row.textContent.toLowerCase();

        if (text.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});

/* FILTER STATUS EXACT */

function filterStatusExact(status){

    let rows = document.querySelectorAll("#dataTable tbody tr");

    rows.forEach(function(row){

        let rowStatus = row.getAttribute("data-status");

        if(rowStatus === status){

            row.style.display = "";

        }else{

            row.style.display = "none";

        }

    });

}

/* SHOW ALL DATA */

function showAllData(){

    let rows = document.querySelectorAll("#dataTable tbody tr");

    rows.forEach(function(row){

        row.style.display = "";

    });

}

/* DELETE MODAL */

let selectedDeleteForm = null;

function openDeleteModal(button){

    selectedDeleteForm = button.closest('.deleteForm');

    document.getElementById('deleteModal').style.display = 'flex';

}

function closeDeleteModal(){

    document.getElementById('deleteModal').style.display = 'none';

}

function submitDeleteForm(){

    if(selectedDeleteForm){

        selectedDeleteForm.submit();

    }

}

/* CLOSE MODAL JIKA KLIK LUAR */

document.getElementById('deleteModal').addEventListener('click', function(e){

    if(e.target === this){

        closeDeleteModal();

    }

});

/* AUTO HILANG ALERT */

setTimeout(() => {

    let toast = document.getElementById('successToast');

    if(toast){

        toast.remove();

    }

}, 3000);

/* EDIT MODAL */

function openEditModal(id, kode, barang, vendor){

    document.getElementById('editModal').style.display = 'block';

    document.getElementById('editOverlay').style.display = 'block';

    document.getElementById('editKode').value = kode;

    document.getElementById('editBarang').value = barang;

    document.getElementById('editVendor').value = vendor;

    document.getElementById('editForm').action =
        '/procurement/update/' + id;

}

function closeEditModal(){

    document.getElementById('editModal').style.display = 'none';

    document.getElementById('editOverlay').style.display = 'none';

}

</script>

</body>
</html>