<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - Pengadaan Barang PT KMI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

/* RESET */
*{
box-sizing:border-box;
font-family:Arial, sans-serif;
}

body{
margin:0;
padding:0;
min-height:100vh;
overflow:hidden;
background:#0b1220;
display:flex;
align-items:center;
justify-content:center;
}

/* BACKGROUND ANIMATION */
.bg{
position:fixed;
width:100%;
height:100%;
top:0;
left:0;
background:url("{{ asset('images/kmi1.jpg') }}") center/cover no-repeat;
filter:blur(2px) brightness(0.6);
transform:scale(1.1);
z-index:0;
animation:zoom 20s infinite alternate;
}

@keyframes zoom{
0%{transform:scale(1.1);}
100%{transform:scale(1.2);}
}

/* OVERLAY DARK */
.overlay{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.4);
z-index:1;
}

/* LOGIN CARD GLASS */
.login-box{
position:relative;
z-index:2;
width:100%;
max-width:380px;
padding:35px;
border-radius:18px;

/* GLASS EFFECT */
background:rgba(255,255,255,0.10);
backdrop-filter:blur(12px);
-webkit-backdrop-filter:blur(12px);

border:1px solid rgba(255,255,255,0.2);
box-shadow:0 20px 40px rgba(0,0,0,0.4);

/* FADE ANIMATION */
animation:fadeIn 1s ease;
}

@keyframes fadeIn{
from{
opacity:0;
transform:translateY(30px);
}
to{
opacity:1;
transform:translateY(0);
}
}

/* HEADER TEXT */
.title{
text-align:center;
color:#fff;
font-weight:bold;
margin-bottom:5px;
letter-spacing:1px;
}

.subtitle{
text-align:center;
color:#cfd8ff;
font-size:13px;
margin-bottom:25px;
}

/* INPUT */
.input-group{
margin-bottom:18px;
position:relative;
}

.input-group i{
position:absolute;
top:50%;
left:12px;
transform:translateY(-50%);
color:#aaa;
}

.input-group input{
width:100%;
padding:12px 12px 12px 38px;
border-radius:10px;
border:none;
outline:none;
background:rgba(255,255,255,0.15);
color:#fff;
transition:0.3s;
}

.input-group input:focus{
background:rgba(255,255,255,0.25);
}

/* PLACEHOLDER */
input::placeholder{
color:#ddd;
}

/* REMEMBER */
.remember{
display:flex;
justify-content:space-between;
align-items:center;
font-size:13px;
color:#ddd;
margin-bottom:20px;
flex-wrap:wrap;
gap:8px;
}

.remember a{
color:#8ab4ff;
text-decoration:none;
}

/* BUTTON */
.login-btn{
width:100%;
padding:12px;
border:none;
border-radius:10px;
background:#3b82f6;
color:white;
font-weight:bold;
cursor:pointer;
transition:0.3s;
display:flex;
align-items:center;
justify-content:center;
gap:10px;
}

.login-btn:hover{
background:#2563eb;
transform:scale(1.03);
}

/* LOADING SPINNER */
.spinner{
display:none;
width:18px;
height:18px;
border:2px solid #fff;
border-top:2px solid transparent;
border-radius:50%;
animation:spin 0.8s linear infinite;
}

@keyframes spin{
to{transform:rotate(360deg);}
}

/* RESPONSIVE */
@media (max-width:480px){
.login-box{
margin:20px;
padding:25px;
}
}

</style>

</head>

<body>

<!-- BACKGROUND -->
<div class="bg"></div>
<div class="overlay"></div>

<!-- CENTER WRAPPER -->
<div class="wrapper">

<!-- LOGIN BOX -->
<div class="login-box">

<h3 class="title">PENGADAAN BARANG</h3>
<div class="subtitle">PT KMI</div>

@if(session('status'))
<div class="alert alert-success py-2">
{{ session('status') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger py-2">
{{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('login') }}" onsubmit="startLoading()">
@csrf

<div class="input-group">
<i class="bi bi-person"></i>
<input type="email" name="email" placeholder="Email Admin" required>
</div>

<div class="input-group">
<i class="bi bi-lock"></i>
<input type="password" name="password" placeholder="Password" required>
</div>

<div class="remember">
<label><input type="checkbox" name="remember"> Remember me</label>
<a href="{{ route('password.request') }}">Forgot?</a>
</div>

<button class="login-btn" id="btnLogin" type="submit">
<span class="spinner" id="spinner"></span>
<span id="btnText">Sign In</span>
</button>

</form>

</div>

</div>

<script>
function startLoading(){
document.getElementById("spinner").style.display="block";
document.getElementById("btnText").innerText="Loading...";
document.getElementById("btnLogin").disabled=true;
}
</script>

</body>
</html>