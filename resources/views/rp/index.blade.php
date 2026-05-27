<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Request Purchasing</title>

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
    justify-content:space-between;
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
    padding:25px 20px;
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

.welcome-text{
    margin-top:5px;
    margin-left:6px;
    margin-bottom:8px;
    font-size:30px;
    color:black;
    font-weight:800;
}

.welcome-text b{
    font-weight:800;
}

.card-box{
    background:rgba(255,255,255,0.96);
    backdrop-filter:blur(6px);
    border-radius:20px;
    padding:25px;
    margin-top:1px;
    box-shadow:0 5px 15px rgba(0,0,0,0.10);
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

.table{
    margin-top:10px;
}

.table th{
    background:#2f3f52 !important;
    color:white !important;
    text-align:center;
    padding:14px;
    font-size:16px;
}

.table td{
    vertical-align:middle;
    text-align:center;
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

.modal-overlay{
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

.modal-box{
    width:450px;
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    animation:popupScale 0.25s ease;
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

.modal-box h3{
    font-weight:bold;
    margin-bottom:20px;
    color:#264a67;
    text-align:center;
}

.form-control,
.form-select{
    height:50px;
    border-radius:12px;
    margin-bottom:18px;
}

textarea.form-control{
    height:auto;
}

.btn-close2{
    width:100%;
    border:none;
    background:#6c757d;
    color:white;
    padding:12px;
    border-radius:10px;
    font-weight:bold;
    margin-top:10px;
}

.btn-close2:hover{
    background:#5c636a;
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
    animation:slideDown 0.4s ease, fadeOut 0.5s ease 2.5s forwards;
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
    z-index:999999;
}

.delete-modal-box{
    width:360px;
    background:white;
    border-radius:22px;
    padding:32px 28px;
    text-align:center;
    animation:popupScale 0.25s ease;
    box-shadow:0 15px 35px rgba(0,0,0,0.25);
}

.delete-icon{
    width:78px;
    height:78px;
    border-radius:50%;
    border:4px solid #ff6b6b;
    color:#ff6b6b;
    font-size:40px;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    margin-bottom:20px;
}

.delete-title{
    font-size:19px;
    font-weight:700;
    color:#333;
    margin-bottom:10px;
}

.delete-subtitle{
    font-size:14px;
    color:#666;
    margin-bottom:25px;
    line-height:1.5;
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

.btn-hapus{
    background:#dc3545;
    color:white;
    border:none;
    padding:10px 24px;
    border-radius:8px;
    font-weight:600;
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
    padding:15px;
}

.topbar{
    border-radius:20px;
}

.modal-box{
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

            <a class="active" href="{{ route('rp.index') }}">
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

            <a href="{{ route('po.index') }}">
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
        REQUEST PURCHASING (RP)
    </div>

    <div class="card-box">

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

            <h3 class="fw-bold m-0">
                Data Request Purchasing
            </h3>

            <button class="btn btn-primary"
                    onclick="openTambahModal()">

                Tambah RP

            </button>

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Kode RP</th>
                        <th>Nama Barang</th>
                        <th>Quantity</th>
                        <th>Departemen</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($rps as $rp)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $rp->kode_rp }}</td>

                    <td>{{ $rp->nama_barang }}</td>

                    <td>{{ $rp->qty }}</td>

                    <td>{{ $rp->departemen }}</td>

                    <td>{{ $rp->keterangan }}</td>

                    <td>

                        @if($rp->status == 'Lolos')

                            <span class="status-lolos">
                                {{ $rp->status }}
                            </span>

                        @elseif($rp->status == 'Tidak Lolos')

                            <span class="status-tidak">
                                {{ $rp->status }}
                            </span>

                        @else

                            <span class="status-pending">
                                {{ $rp->status }}
                            </span>

                        @endif

                    </td>

                    <td>{{ $rp->tanggal }}</td>

                    <td>

                        <button
                            class="btn-warning"
                            onclick="openEditModal(
                                '{{ $rp->id }}',
                                '{{ $rp->kode_rp }}',
                                '{{ $rp->nama_barang }}',
                                '{{ $rp->qty }}',
                                '{{ $rp->departemen }}',
                                '{{ $rp->keterangan }}',
                                '{{ $rp->status }}',
                                '{{ $rp->tanggal }}'
                            )">

                            Edit

                        </button>

                        <form action="{{ route('rp.delete', $rp->id) }}"
                              method="POST"
                              style="display:inline-block;"
                              class="deleteForm">

                            @csrf
                            @method('DELETE')

                            <button type="button"
                                    class="btn-danger"
                                    onclick="openDeleteModal(this)">

                                Hapus

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="9">
                        Data Request Purchasing Belum Ada
                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- MODAL TAMBAH -->

<div class="modal-overlay" id="tambahModal">

    <div class="modal-box">

        <h3>Tambah Request Purchasing</h3>

        <form action="{{ route('rp.store') }}" method="POST">

            @csrf

            <label class="fw-bold mb-2">Kode RP</label>

            <input type="text"
                   name="kode_rp"
                   class="form-control"
                   placeholder="Masukkan Kode RP"
                   required>

            <label class="fw-bold mb-2">Nama Barang</label>

            <input type="text"
                   name="nama_barang"
                   class="form-control"
                   placeholder="Masukkan Nama Barang"
                   required>

            <label class="fw-bold mb-2">Quantity</label>

            <input type="number"
                   name="qty"
                   class="form-control"
                   placeholder="Masukkan Quantity"
                   required>

            <label class="fw-bold mb-2">Departemen</label>

            <input type="text"
                   name="departemen"
                   class="form-control"
                   placeholder="Masukkan Departemen"
                   required>

            <label class="fw-bold mb-2">Keterangan</label>

            <textarea
                name="keterangan"
                class="form-control"
                placeholder="Masukkan Keterangan"
                rows="3"></textarea>

            <label class="fw-bold mb-2">Status</label>

            <select name="status"
                    class="form-select"
                    required>

                <option value="">-- Pilih Status --</option>
                <option value="Lolos">Lolos</option>
                <option value="Tidak Lolos">Tidak Lolos</option>
                <option value="Pending">Pending</option>

            </select>

            <label class="fw-bold mb-2">Tanggal</label>

            <input type="date"
                   name="tanggal"
                   class="form-control"
                   required>

            <button type="submit"
                    class="btn btn-primary w-100">

                Simpan RP

            </button>

            <button type="button"
                    class="btn-close2"
                    onclick="closeTambahModal()">

                Kembali

            </button>

        </form>

    </div>

</div>

<!-- MODAL EDIT -->

<div class="modal-overlay" id="editModal">

    <div class="modal-box">

        <h3>Edit Request Purchasing</h3>

        <form id="editForm" method="POST">

            @csrf
            @method('PUT')

            <label class="fw-bold mb-2">Kode RP</label>

            <input type="text"
                   name="kode_rp"
                   id="editKodeRp"
                   class="form-control"
                   required>

            <label class="fw-bold mb-2">Nama Barang</label>

            <input type="text"
                   name="nama_barang"
                   id="editNamaBarang"
                   class="form-control"
                   required>

            <label class="fw-bold mb-2">Quantity</label>

            <input type="number"
                   name="qty"
                   id="editQty"
                   class="form-control"
                   required>

            <label class="fw-bold mb-2">Departemen</label>

            <input type="text"
                   name="departemen"
                   id="editDepartemen"
                   class="form-control"
                   required>

            <label class="fw-bold mb-2">Keterangan</label>

            <textarea
                name="keterangan"
                id="editKeterangan"
                class="form-control"
                rows="3"></textarea>

            <label class="fw-bold mb-2">Status</label>

            <select name="status"
                    id="editStatus"
                    class="form-select"
                    required>

                <option value="Request Purchasing">Request Purchasing</option>

            </select>

            <label class="fw-bold mb-2">Tanggal</label>

            <input type="date"
                   name="tanggal"
                   id="editTanggal"
                   class="form-control"
                   required>

            <button type="submit"
                    class="btn btn-primary w-100">

                Simpan RP

            </button>

            <button type="button"
                    class="btn-close2"
                    onclick="closeEditModal()">

                Kembali

            </button>

        </form>

    </div>

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

/* MODAL TAMBAH */

function openTambahModal(){

    document.getElementById('tambahModal').style.display = 'flex';

}

function closeTambahModal(){

    document.getElementById('tambahModal').style.display = 'none';

}

/* MODAL EDIT */

function openEditModal(
    id,
    kode_rp,
    nama_barang,
    qty,
    departemen,
    keterangan,
    status,
    tanggal
){

    document.getElementById('editModal').style.display = 'flex';

    document.getElementById('editKodeRp').value = kode_rp;
    document.getElementById('editNamaBarang').value = nama_barang;
    document.getElementById('editQty').value = qty;
    document.getElementById('editDepartemen').value = departemen;
    document.getElementById('editKeterangan').value = keterangan;
    document.getElementById('editStatus').value = status;
    document.getElementById('editTanggal').value = tanggal;

    document.getElementById('editForm').action =
        '/rp/update/' + id;

}

function closeEditModal(){

    document.getElementById('editModal').style.display = 'none';

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

/* CLOSE MODAL */

window.onclick = function(event){

    let tambahModal = document.getElementById('tambahModal');
    let editModal = document.getElementById('editModal');
    let deleteModal = document.getElementById('deleteModal');

    if(event.target == tambahModal){

        tambahModal.style.display = "none";

    }

    if(event.target == editModal){

        editModal.style.display = "none";

    }

    if(event.target == deleteModal){

        deleteModal.style.display = "none";

    }

}

/* AUTO HILANG ALERT */

setTimeout(() => {

    let toast = document.getElementById('successToast');

    if(toast){

        toast.remove();

    }

}, 3000);

</script>

</body>
</html>