<?php
session_start();

$conn = new mysqli("localhost", "root", "", "apartman_db");
$conn->set_charset("utf8");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 🔐 HASH + eski düz şifre uyumu
        if (
            password_verify($password, $user["password"]) ||
            $user["password"] === $password
        ) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: admin.php");
            } elseif ($user["role"] === "kapici") {
                header("Location: kapici.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }

    $error = "E-posta veya şifre yanlış";
}
?>

<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Giriş Yap</title>
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

<h4 class="text-center mb-3">🔐 Giriş Yap</h4>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="post">
<input type="email" name="email" class="form-control mb-3" placeholder="E-posta" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Şifre" required>
<button class="btn btn-main w-100">Giriş</button>
</form>
<div class="text-center mt-3">
    <small>
        Hesabın yok mu?
        <a href="register.php">Kayıt Ol</a>
    </small>
</div>

</div>
</div>
</body>
</html>
