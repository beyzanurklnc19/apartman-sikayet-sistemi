<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "apartman_db");
$conn->set_charset("utf8");

$users = $conn->query("
    SELECT id, name, email, role, apartment_name, flat_no
    FROM users
    ORDER BY id DESC
");
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Kullanıcılar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f8f7f4; }
.card {
  border-radius:16px;
  box-shadow:0 8px 20px rgba(0,0,0,.08);
}
</style>
</head>

<body>
<div class="container my-5">

<div class="d-flex justify-content-between align-items-center mb-3">
<h2>👥 Kullanıcılar</h2>
<a href="admin.php" class="btn btn-outline-secondary">← Admin Paneli</a>
</div>

<div class="card p-4">
<table class="table">
<thead>
<tr>
<th>#</th>
<th>Ad Soyad</th>
<th>E-posta</th>
<th>Apartman</th>
<th>Daire</th>
<th>Rol</th>
</tr>
</thead>
<tbody>

<?php while($u = $users->fetch_assoc()): ?>
<tr>
<td><?= $u["id"] ?></td>
<td><?= htmlspecialchars($u["name"]) ?></td>
<td><?= htmlspecialchars($u["email"]) ?></td>
<td><?= htmlspecialchars($u["apartment_name"] ?? "-") ?></td>
<td><?= htmlspecialchars($u["flat_no"] ?? "-") ?></td>
<td>
<span class="badge bg-info text-dark"><?= $u["role"] ?></span>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</div>
</body>
</html>
