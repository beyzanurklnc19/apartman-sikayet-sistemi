<?php
session_start();
require "db.php";

// admin kontrolü
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

// kullanıcıları çek
$stmt = $conn->query("SELECT id, name, surname, email, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container my-5">

<div class="d-flex justify-content-between align-items-center mb-4">
<h2>👑 Admin Paneli</h2>
<a href="logout.php" class="btn btn-outline-secondary">Çıkış</a>
</div>

<div class="card p-4">
<h5>Kullanıcılar</h5>

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

</div>
</body>
</html>
