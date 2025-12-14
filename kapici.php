<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "kapici") {
    header("Location: login.php");
    exit;
}

// DURUM GÜNCELLEME
if (isset($_POST["complaint_id"])) {
    $stmt = $conn->prepare("
        UPDATE complaints 
        SET status = 'Çözüldü' 
        WHERE id = :id
    ");
    $stmt->execute([
        ":id" => $_POST["complaint_id"]
    ]);
}

// TÜM ŞİKAYETLER
$complaints = $conn->query("
    SELECT c.id, c.description, c.status,
           u.name, u.surname
    FROM complaints c
    JOIN users u ON c.user_id = u.id
    ORDER BY c.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
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
<div class="d-flex justify-content-between mb-3">
<h3>🧹 Kapıcı Paneli</h3>
<a href="logout.php" class="btn btn-outline-secondary">Çıkış</a>
</div>

<table class="table table-bordered bg-white">
<thead>
<tr>
<th>#</th>
<th>Sakin</th>
<th>Şikayet</th>
<th>Durum</th>
<th>İşlem</th>
</tr>
</thead>
<tbody>

<?php foreach ($complaints as $c): ?>
<tr>
<td><?= $c["id"] ?></td>
<td><?= $c["name"] . " " . $c["surname"] ?></td>
<td><?= $c["description"] ?></td>
<td><?= $c["status"] ?></td>
<td>
<?php if ($c["status"] !== "Çözüldü"): ?>
<form method="post">
<input type="hidden" name="complaint_id" value="<?= $c["id"] ?>">
<button class="btn btn-success btn-sm">Çözüldü</button>
</form>
<?php else: ?>
✔
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>
</body>
</html>
