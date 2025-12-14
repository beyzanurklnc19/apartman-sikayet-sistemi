<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

// KULLANICILAR
$users = $conn->query("
    SELECT id, name, surname, email, role
    FROM users
    ORDER BY id
")->fetchAll(PDO::FETCH_ASSOC);

// ŞİKAYETLER
$complaints = $conn->query("
    SELECT c.id, c.description, c.status,
           u.name, u.surname, u.email
    FROM complaints c
    JOIN users u ON c.user_id = u.id
    ORDER BY c.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Admin Paneli</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container my-5">

<div class="d-flex justify-content-between mb-4">
<h2>👑 Admin Paneli</h2>
<a href="logout.php" class="btn btn-outline-secondary">Çıkış</a>
</div>

<!-- KULLANICILAR -->
<div class="card p-4 mb-5">
<h4>👥 Kullanıcılar</h4>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>ID</th>
<th>İsim</th>
<th>Soyisim</th>
<th>E-posta</th>
<th>Rol</th>
</tr>
</thead>
<tbody>

<?php foreach ($users as $u): ?>
<tr>
<td><?= $u["id"] ?></td>
<td><?= $u["name"] ?></td>
<td><?= $u["surname"] ?></td>
<td><?= $u["email"] ?></td>
<td><?= $u["role"] ?></td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

<!-- ŞİKAYETLER -->
<div class="card p-4">
<h4>📄 Şikayetler</h4>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>#</th>
<th>Sakin</th>
<th>E-posta</th>
<th>Şikayet</th>
<th>Durum</th>
</tr>
</thead>
<tbody>

<?php foreach ($complaints as $c): ?>
<tr>
<td><?= $c["id"] ?></td>
<td><?= $c["name"] . " " . $c["surname"] ?></td>
<td><?= $c["email"] ?></td>
<td><?= $c["description"] ?></td>
<td><?= $c["status"] ?></td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</div>
</body>
</html>
