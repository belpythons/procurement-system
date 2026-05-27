<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - PENGADAAN BARANG</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:Arial, Helvetica, sans-serif;
height:100vh;
overflow:hidden;
}

/* MAIN */

.main-container{
position:relative;
width:100%;
height:100vh;
overflow:hidden;
}

/* BACKGROUND */

.left-side{
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
background:url("{{ asset('images/kmi1.jpg') }}");
background-size:cover;
background-position:center;
background-repeat:no-repeat;
transform:scaleX(-1);
}

/* OVERLAY */

.left-side::after{
content:"";
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.12);
}

/* HEADER */

.top-header{
position:absolute;
top:0;
left:0;
width:100%;
height:155px;
background:#3f5681;
clip-path:polygon(0 0,100% 0,100% 62%,0 100%);
z-index:10;
}

/* LOGO */

.top-header img{
position:absolute;
left:30px;
top:18px;
width:220px;
}

/* TITLE */

.top-header h2{
position:absolute;
top:38px;
left:50%;
transform:translateX(-50%);
color:white;
font-size:44px;
font-weight:700;
letter-spacing:1px;
margin:0;
}

/* LOGIN POSITION */

.right-side{
position:absolute;
top:55%;
right:9%;
transform:translateY(-50%);
z-index:20;
}

/* LOGIN CARD */

.login-card{
width:360px;
padding:38px;
border-radius:18px;
background:rgba(255,255,255,0.20);
backdrop-filter:blur(10px);
-webkit-backdrop-filter:blur(10px);
box-shadow:0 8px 30px rgba(0,0,0,0.25);
}

/* LOGIN TITLE */

.login-card h4{
color:white;
font-size:22px;
font-weight:bold;
margin-bottom:25px;
text-align:center;
}

/* INPUT GROUP */

.input-group{
margin-bottom:20px;
}

.input-group-text{
background:white;
border:none;
border-radius:8px 0 0 8px;
}

.form-control{
height:50px;
border:none;
background:rgba(255,255,255,0.95);
font-size:15px;
}

.form-control:focus{
box-shadow:none;
}

/* EYE BUTTON */

.show-pass{
border:none;
background:white;
border-radius:0 8px 8px 0;
padding:0 15px;
}

/* BUTTON */

.login-btn{
width:100%;
height:50px;
border:none;
border-radius:8px;
background:#2F6DE1;
color:white;
font-size:18px;
font-weight:bold;
transition:0.3s;
margin-top:5px;
}

.login-btn:hover{
background:#1f57bd;
}

/* ALERT */

.alert{
border-radius:8px;
font-size:14px;
}

/* RESPONSIVE */

@media(max-width:768px){

.top-header{
height:110px;
}

.top-header img{
width:120px;
left:10px;
top:12px;
}

.top-header h2{
font-size:22px;
top:35px;
width:100%;
padding:0 70px;
text-align:center;
}

.right-side{
right:50%;
top:58%;
transform:translate(50%,-50%);
width:100%;
display:flex;
justify-content:center;
}

.login-card{
width:90%;
padding:30px;
}

}

</style>

</head>

<body>

<div class="top-header">
<img src="{{ asset('images/kmi-logo.png') }}">
<h2>PENGADAAN BARANG - PT KMI</h2>
</div>

<div class="main-container">

<div class="left-side"></div>

<div class="right-side">

<div class="login-card">

<h4>Login Admin</h4>

@if($errors->any())
<div class="alert alert-danger">
{{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('login') }}">
@csrf

<!-- EMAIL -->

<div class="input-group">

<span class="input-group-text">
<i class="bi bi-envelope-fill"></i>
</span>

<input 
type="email"
name="email"
class="form-control"
value="admin@outlook.com"
placeholder="Email Admin"
required>

</div>

<!-- PASSWORD -->

<div class="input-group">

<span class="input-group-text">
<i class="bi bi-lock-fill"></i>
</span>

<input 
type="password"
name="password"
id="password"
class="form-control"
value="adminkmi123"
placeholder="Password"
required>

<button 
type="button"
class="show-pass"
onclick="togglePassword()">

<i class="bi bi-eye-fill" id="eyeIcon"></i>

</button>

</div>

<button type="submit" class="login-btn">
Login
</button>

</form>

</div>

</div>

</div>

<script>

function togglePassword(){

let password = document.getElementById('password');
let eyeIcon = document.getElementById('eyeIcon');

if(password.type === "password"){

password.type = "text";

eyeIcon.classList.remove('bi-eye-fill');
eyeIcon.classList.add('bi-eye-slash-fill');

}else{

password.type = "password";

eyeIcon.classList.remove('bi-eye-slash-fill');
eyeIcon.classList.add('bi-eye-fill');

}

}

</script>

</body>
</html>