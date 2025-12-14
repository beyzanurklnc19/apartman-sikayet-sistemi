<?php
session_start();
require_once "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $role = "sakin"; // kayıt olan herkes sakin

    if ($email === "" || $password === "") {
        $error = "Tüm alanları doldurun.";
    } else {
        // aynı mail var mı kontrol et
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $error = "Bu e-posta zaten kayıtlı.";
        } else {
            // şifreyi hashle
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (email, password, role) VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $success = "Kayıt başarılı. Giriş yapabilirsiniz.";
            } else {
                $error = "Kayıt sırasında hata oluştu.";
            }
        }
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
body { background:#f8f7f4; }
.card {
  border-radius:16px;
  box-shadow:0 8px 20px rgba(0,0,0,.08);
}
.btn-main {
  background:#8fb9a8;
  color:white;
}
</style>
</head>

<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh">
<div class="card p-4" style="width:380px">

<h4 class="text-center mb-3">📝 Kayıt Ol</h4>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post">
<input type="email" name="email" class="form-control mb-3" placeholder="E-posta" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Şifre" required>
<button class="btn btn-main w-100">Kayıt Ol</button>
</form>

<div class="text-center mt-3">
    <small>
        Zaten hesabın var mı?
        <a href="login.php">Giriş Yap</a>
    </small>
</div>

</div>
</div>
</body>
</html>
