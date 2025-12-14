<?php
$conn = new mysqli("localhost", "root", "", "apartman_db");
$conn->set_charset("utf8");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $apartment = $_POST["apartment_name"];
    $flat = $_POST["flat_no"];

    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Bu e-posta zaten kayıtlı.";
    } else {
        $conn->query("
            INSERT INTO users (name, email, password, role, apartment_name, flat_no)
            VALUES ('$name', '$email', '$password', 'sakin', '$apartment', '$flat')
        ");
        $success = "Kayıt başarılı! Giriş yapabilirsiniz.";
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Kayıt Ol</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background:#f8f7f4;
}
.card {
    max-width:420px;
    margin:auto;
    margin-top:80px;
    border-radius:16px;
}
.btn-main {
    background:#8fb9a8;
    color:white;
}
</style>
</head>
<body>

<div class="card p-4 shadow">
<h4 class="mb-3">📝 Kayıt Ol</h4>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post">
    <input class="form-control mb-2" name="name" placeholder="Ad Soyad" required>
    <input class="form-control mb-2" name="email" type="email" placeholder="E-posta" required>
    <input class="form-control mb-2" name="password" type="password" placeholder="Şifre" required>
    <input class="form-control mb-2" name="apartment_name" placeholder="Apartman Adı" required>
    <input class="form-control mb-3" name="flat_no" placeholder="Daire No" required>

    <button class="btn btn-main w-100">Kayıt Ol</button>
</form>

<div class="text-center mt-3">
<a href="login.php">Giriş yap</a>
</div>

</div>
</body>
</html>
