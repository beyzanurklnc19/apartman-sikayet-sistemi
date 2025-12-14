<?php
session_start();
require "db.php";

// Giriş kontrolü
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// SADECE NORMAL KULLANICI (SAKİN)
if ($_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit;
}
?>
<?php
session_start();
require "db.php";

// giriş kontrolü
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// FORM GÖNDERİLDİYSE
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = $_POST["category_id"] ?? null;
    $description = trim($_POST["description"] ?? "");

    if ($description !== "") {
        $stmt = $conn->prepare("
            INSERT INTO complaints (user_id, category_id, description, status)
            VALUES (:user_id, :category_id, :description, 'Beklemede')
        ");
        $stmt->execute([
            ":user_id" => $user_id,
            ":category_id" => $category_id,
            ":description" => $description
        ]);
    }
}

// KATEGORİLER
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// SADECE BU KULLANICININ ŞİKAYETLERİ
$stmt = $conn->prepare("
    SELECT c.id, c.description, c.status, cat.name AS category
    FROM complaints c
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.user_id = :user_id
    ORDER BY c.id DESC
");
$stmt->execute([":user_id" => $user_id]);
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Apartman Şikayet Sistemi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f8f7f4; }
.card {
  border-radius:16px;
  border:none;
  box-shadow:0 8px 20px rgba(0,0,0,.08);
}
.btn-main {
  background:#8fb9a8;
  color:white;
  border-radius:10px;
}
</style>
</head>

<body>
<div class="container my-5">

<div class="d-flex justify-content-between align-items-center mb-3">
<h2>🏡 Apartman Şikayet Sistemi</h2>
<a href="logout.php" class="btn btn-outline-secondary">Çıkış</a>
</div>

<div class="card p-4 my-4">
<h5>Yeni Şikayet Oluştur</h5>

<form method="post">
<select name="category_id" class="form-select mb-3">
<option value="">Kategori seçiniz</option>
<?php foreach ($categories as $cat): ?>
<option value="<?= $cat["id"] ?>"><?= $cat["name"] ?></option>
<?php endforeach; ?>
</select>

<textarea name="description" class="form-control mb-3"
placeholder="Şikayetinizi yazınız"></textarea>

<button class="btn btn-main">Gönder</button>
</form>
</div>

<div class="card p-4">
<h5>Şikayetlerim</h5>

<table class="table">
<thead>
<tr>
<th>#</th>
<th>Kategori</th>
<th>Şikayet</th>
<th>Durum</th>
</tr>
</thead>
<tbody>

<?php foreach ($complaints as $row): ?>
<tr>
<td><?= $row["id"] ?></td>
<td><?= $row["category"] ?? "—" ?></td>
<td><?= $row["description"] ?></td>
<td>
<span class="badge <?= $row["status"]=="Çözüldü" ? "bg-success" : "bg-warning text-dark" ?>">
<?= $row["status"] ?>
</span>
</td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</div>
</body>
</html>

