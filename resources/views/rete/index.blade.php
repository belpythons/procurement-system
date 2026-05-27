<!DOCTYPE html>
<html>
<head>

    <title>Re-Technical Evaluation</title>

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

/* CARD */
.card-box{
    background:rgba(255,255,255,0.96);
    border-radius:20px;
    padding:25px;
    box-shadow:0 5px 15px rgba(0,0,0,0.10);
}

/* BUTTON AKSI */
.btn-warning,
.btn-danger{
    width:50px;
    height:27px;
    border:none;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    padding:0;
    color:white;
}

/* EDIT */
.btn-warning{
    background:#f4b400;
}

.btn-warning:hover{
    background:#d89c00;
    color:white;
}

/* HAPUS */
.btn-danger{
    background:#dc3545;
}

.btn-danger:hover{
    background:#bb2d3b;
}

/* TABLE */
.table th{
    background:#2f3f52 !important;
    color:white !important;
    text-align:center;
    padding:14px;
}

.table tbody tr,
.table tbody td{
    background:#fff !important;
    color:#000 !important;
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
    color:white;
}

/* MODAL */
.modal-content{
    border-radius:25px;
}

.modal-title{
    font-weight:bold;
    font-size:30px;
    text-align:center;
}

.form-control,
.form-select{
    border-radius:10px;
}

textarea.form-control{
    height:auto;
}

/* TOAST */
.toast-success{
    position:fixed;
    top:20px;
    left:50%;
    transform:translateX(-50%);
    background:white;
    min-width:350px;
    padding:15px 20px;
    border-radius:15px;
    display:flex;
    align-items:center;
    gap:15px;
    box-shadow:0 8px 25px rgba(0,0,0,0.18);
    z-index:999999;
    border-left:6px solid #22c55e;
    animation:slideDown 0.4s ease;
}

.toast-success i{
    font-size:28px;
    color:#22c55e;
}

.toast-text{
    flex:1;
    font-weight:600;
}

.close-toast{
    cursor:pointer;
    font-size:20px;
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

            <a class="active" href="{{ route('rete.index') }}">
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
        <b>RE-TECHNICAL EVALUATION</b> (RE-TE)
    </div>

    <div class="card-box">

        <div class="d-flex justify-content-between mb-3">

            <h3 class="fw-bold m-0">
                Data Re-Technical Evaluation
            </h3>

            <button class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">

                Tambah RE-TE

            </button>

        </div>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Vendor</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($rete as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->kode_rete }}</td>

                    <td>{{ $item->nama_barang }}</td>

                    <td>{{ $item->vendor }}</td>

                    <td>{{ $item->catatan }}</td>

                    <td>{{ $item->tanggal }}</td>

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

                    <td>

    <div class="d-flex justify-content-center gap-2">

        <button class="btn btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#modalEdit{{ $item->id }}">

            Edit

        </button>

        <button class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#modalDelete{{ $item->id }}">

            Hapus

        </button>

    </div>

</td>

                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade"
                     id="modalEdit{{ $item->id }}"
                     tabindex="-1">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content">

                            <div class="modal-body p-4">

                                <h2 class="modal-title mb-4">
                                    Edit RE-TE
                                </h2>

                                <form action="{{ route('rete.update', $item->id) }}"
                                      method="POST">

                                    @csrf

                                    <div class="mb-3">
                                        <label>Kode RE-TE</label>

                                        <input type="text"
                                               name="kode_rete"
                                               class="form-control"
                                               value="{{ $item->kode_rete }}"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Nama Barang</label>

                                        <input type="text"
                                               name="nama_barang"
                                               class="form-control"
                                               value="{{ $item->nama_barang }}"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Vendor</label>

                                        <input type="text"
                                               name="vendor"
                                               class="form-control"
                                               value="{{ $item->vendor }}"
                                               required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Catatan</label>

                                        <textarea name="catatan"
                                                  class="form-control"
                                                  rows="3"
                                                  required>{{ $item->catatan }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Tanggal</label>

                                        <input type="date"
                                               name="tanggal"
                                               class="form-control"
                                               value="{{ $item->tanggal }}"
                                               required>
                                    </div>

                                    <div class="mb-4">

                                        <label>Status</label>

                                        <select name="status"
                                                class="form-select"
                                                required>

                                            <option value="Lolos"
                                            {{ $item->status == 'Lolos' ? 'selected' : '' }}>
                                                Lolos
                                            </option>

                                            <option value="Tidak Lolos"
                                            {{ $item->status == 'Tidak Lolos' ? 'selected' : '' }}>
                                                Tidak Lolos
                                            </option>

                                            <option value="Pending"
                                            {{ $item->status == 'Pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>

                                        </select>

                                    </div>

                                    <button type="submit"
                                            class="btn btn-primary w-100 mb-2">

                                        Update RE-TE

                                    </button>

                                    <button type="button"
                                            class="btn btn-secondary w-100"
                                            data-bs-dismiss="modal">

                                        Kembali

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- MODAL DELETE -->
                <div class="modal fade"
                    id="modalDelete{{ $item->id }}"
                    tabindex="-1"
                    aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered"
                        style="max-width:390px;">

                        <div class="modal-content border-0"
                            style="
                                border-radius:22px;
                                padding:35px 30px;
                                text-align:center;
                                box-shadow:0 15px 40px rgba(0,0,0,0.25);
                            ">
                            
                        <!-- ICON -->
                        <div style="
                            width:90px;
                            height:90px;
                            border:4px solid #ff6b6b;
                            border-radius:50%;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            margin:auto;
                            margin-bottom:20px;
                        ">
                        
                            <i class="bi bi-exclamation-lg"
                                style="
                                    font-size:45px;
                                    color:#ff6b6b;
                                    font-weight:bold;
                                    
                                ">
                            </i>
                        
                        </div>

                        <!-- TITLE -->
                        <div style="
                            font-size:20px;
                            font-weight:800;
                            color:#333;
                            margin-bottom:10px;
                        ">

                            Yakin Hapus Data Ini?
                        
                        </div>

                        <!-- TEXT -->
                        <div style="
                            color:#777;
                            font-size:15px;
                            margin-bottom:25px;
                        ">

                            Data yang dihapus tidak dapat dikembalikan lagi

                        </div>

                        <!-- BUTTON -->
                        <div class="d-flex justify-content-center gap-3">

                        <!-- BATAL -->
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-dismiss="modal"
                                style="
                                    padding:10px 25px;
                                    border-radius:10px;
                                    font-weight:700;
                                    min-width:110px;
                                ">

                            Batal

                        </button>

                        <!-- HAPUS -->
                        <form action="{{ route('rete.delete', $item->id) }}"
                            method="POST"
                            style="margin:0;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger"
                                    style="
                                        padding:10px 25px;
                                        border-radius:10px;
                                        font-weight:700;
                                        min-width:110px;
                                    ">

                                Hapus

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

                </div>

            @empty

                <tr>

                    <td colspan="8">
                        Belum ada data RE-TE
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0">

            <div class="modal-body p-4">

                <h2 class="modal-title mb-4">
                    Tambah Re-Technical Evaluation
                </h2>

                <form action="{{ route('rete.store') }}"
                      method="POST">

                    @csrf

                    <input type="hidden"
                           name="te_id"
                           value="1">

                    <div class="mb-3">

                        <label>Kode Re-TE</label>

                        <input type="text"
                               name="kode_rete"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label>Nama Barang</label>

                        <input type="text"
                               name="nama_barang"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label>Vendor</label>

                        <input type="text"
                               name="vendor"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label>Catatan</label>

                        <textarea name="catatan"
                                  class="form-control"
                                  rows="3"
                                  required></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Tanggal</label>

                        <input type="date"
                               name="tanggal"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-4">

                        <label>Status</label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="">
                                -- Pilih Status --
                            </option>

                            <option value="Lolos">
                                Lolos
                            </option>

                            <option value="Tidak Lolos">
                                Tidak Lolos
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                        </select>

                    </div>

                    <button type="submit"
                            class="btn btn-primary w-100 mb-2">

                        Simpan RE-TE

                    </button>

                    <button type="button"
                            class="btn btn-secondary w-100"
                            data-bs-dismiss="modal">

                        Kembali

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

setTimeout(() => {

    let toast = document.getElementById('successToast');

    if(toast){

        toast.remove();

    }

}, 3000);

</script>

</body>
</html>