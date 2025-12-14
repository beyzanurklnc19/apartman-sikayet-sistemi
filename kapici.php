<?php
session_start();
require "db.php";

// kapıcı kontrolü
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "kapici") {
    header("Location: login.php");
    exit;
}

// şikayetleri çek
$stmt = $conn->query("
    SELECT c.id, c.description, c.status, u.name, u.surname
    FROM complaints c
    JOIN users u ON c.user_id = u.id
    ORDER BY c.id DESC
");
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Kapıcı Paneli</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container my-5">

<div class="d-flex justify-content-between align-items-center mb-4">
<h2>🧹 Kapıcı Paneli</h2>
<a href="logout.php" class="btn btn-outline-secondary">Çıkış</a>
</div>

<div class="card p-4">
<h5>Şikayetler</h5>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>#</th>
<th>Sakin</th>
<th>Şikayet</th>
<th>Durum</th>
</tr>
</thead>
<tbody>

<?php foreach ($complaints as $c): ?>
<tr>
<td><?= $c["id"] ?></td>
<td><?= $c["name"] . " " . $c["surname"] ?></td>
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
