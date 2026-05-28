<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Procurement</title>

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
    color:white;
    text-decoration:none;
    transition:0.3s;
}

.sidebar-menu a:hover{
    background:#3e5670;
}

.sidebar-menu .active{
    background:#456ea4;
}

/* LOGOUT */
.logout{
    margin-top:auto;
    padding:25px;
    text-align:center;
}

.logout button{
    width:180px;
    border:none;
    background:#dc3545;
    color:white;
    padding:12px;
    border-radius:12px;
    font-weight:bold;
}

/* CONTENT */
.content{
    margin-left:240px;
    padding:25px;
}

/* TOPBAR */
.topbar{
    background:#264a67;
    border-radius:40px;
    padding:20px 30px;
    margin-bottom:20px;
}

.topbar h5{
    color:white;
    margin:0;
    font-weight:bold;
}

/* CARD */
.card-box{
    background:rgba(255,255,255,0.97);
    border-radius:20px;
    padding:25px;
}

/* TABLE */
.table th{
    background:#2f3f52;
    color:white;
    text-align:center;
}

.table td{
    text-align:center;
    vertical-align:middle;
}

/* STATUS */
.badge{
    padding:8px 15px;
    border-radius:20px;
    font-size:13px;
}

/* BUTTON */
.btn-success{
    border-radius:10px;
    padding:10px 18px;
    font-weight:bold;
}

.btn-danger{
    border-radius:10px;
    padding:10px 18px;
    font-weight:bold;
}

</style>

</head>

<body>

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

            <a class="active" href="{{ route('laporan.index') }}">
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

    </div>

</div>

<!-- CONTENT -->
<div class="content">

    <div class="topbar">

        <h5>
            LAPORAN PROCUREMENT SYSTEM
        </h5>

    </div>

    <div class="card-box">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3 class="fw-bold">
                Data Laporan Procurement
            </h3>

            <div class="d-flex gap-2">

                <a href="{{ route('report.export.excel') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i>
                    Export Excel
                </a>

                <a href="{{ route('report.export.pdf') }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Export PDF
                </a>

            </div>

        </div>

        <table class="table table-bordered table-hover">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Kode Pengadaan</th>
                    <th>Nama Barang</th>
                    <th>Vendor</th>
                    <th>Status</th>
                    <th>Tanggal</th>

                </tr>

            </thead>

            <tbody>

            @forelse($laporan as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->kode_pengadaan }}</td>

                <td>{{ $item->nama_barang }}</td>

                <td>{{ $item->vendor }}</td>

                <td>

                    @if($item->status == 'RP')

                        <span class="badge bg-primary">
                            RP
                        </span>

                    @elseif($item->status == 'TE')

                        <span class="badge bg-success">
                            TE
                        </span>

                    @elseif($item->status == 'RE-TE')

                        <span class="badge bg-warning text-dark">
                            RE-TE
                        </span>

                    @elseif($item->status == 'PO')

                        <span class="badge bg-danger">
                            PO
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            {{ $item->status }}
                        </span>

                    @endif

                </td>

                <td>{{ $item->tanggal?->format('Y-m-d') ?? '-' }}</td>

            </tr>

            @empty

            <tr>

                <td colspan="5" class="text-center text-muted">

                    Data laporan kosong

                </td>

            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>