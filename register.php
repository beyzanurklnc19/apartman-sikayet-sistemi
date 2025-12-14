<?php
require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = $_POST["name"] ?? "";
    $surname  = $_POST["surname"] ?? "";
    $apartment= $_POST["apartment"] ?? "";
    $flat     = $_POST["flat"] ?? "";
    $email    = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($name && $surname && $apartment && $flat && $email && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name, surname, apartment, flat, email, password, role)
             VALUES (?, ?, ?, ?, ?, ?, 'user')"
        );
        $stmt->bind_param(
            "ssssss",
            $name, $surname, $apartment, $flat, $email, $hashed
        );

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $message = "Kayıt sırasında hata oluştu";
        }
    } else {
        $message = "Tüm alanları doldurun";
    }
}
?>

<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Kayıt Ol</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">
<div class="card p-4" style="width:420px">

<h4 class="text-center mb-3">📝 Kayıt Ol</h4>

<?php if ($message): ?>
<div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>

<form method="post">
<input class="form-control mb-2" name="name" placeholder="İsim" required>
<input class="form-control mb-2" name="surname" placeholder="Soyisim" required>
<input class="form-control mb-2" name="apartment" placeholder="Apartman Adı" required>
<input class="form-control mb-2" name="flat" placeholder="Daire No" required>
<input type="email" class="form-control mb-2" name="email" placeholder="E-posta" required>
<input type="password" class="form-control mb-3" name="password" placeholder="Şifre" required>

<button class="btn btn-success w-100">Kayıt Ol</button>
</form>

<div class="text-center mt-3">
<small>Zaten hesabın var mı? <a href="login.php">Giriş Yap</a></small>
</div>

</div>
</div>
</body>
</html>
