<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "apartman_db");
$conn->set_charset("utf8");

$complaints = [];

$sql = "
SELECT 
    c.id,
    c.description,
    c.status,
    cat.name AS category,
    u.name AS user_name,
    u.apartment_name,
    u.flat_no
FROM complaints c
LEFT JOIN categories cat ON c.category_id = cat.id
LEFT JOIN users u ON c.user_id = u.id
ORDER BY c.id DESC
";

$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>Admin Paneli</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f8f7f4; }
.card {
  border-radius:16px;
  box-shadow:0 8px 20px rgba(0,0,0,.08);
}
h2 { color:#5a7f73; }
</style>
</head>

<body>
<div class="container my-5">

<!-- ÜST BAR -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>🛠️ Admin Paneli</h2>
  <div>
    <a href="admin_users.php" class="btn btn-outline-secondary me-2">
      👥 Kullanıcılar
    </a>
    <a href="logout.php" class="btn btn-outline-secondary">
      Çıkış
    </a>
  </div>
</div>

<!-- ŞİKAYETLER -->
<div class="card p-4">
  <h5 class="mb-3">Tüm Şikayetler</h5>

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Kategori</th>
        <th>Şikayet</th>
        <th>Şikayeti Yapan</th>
        <th>Apartman</th>
        <th>Daire</th>
        <th>Durum</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($complaints as $c): ?>
      <tr>
        <td><?= $c["id"] ?></td>
        <td><?= $c["category"] ?? "—" ?></td>
        <td><?= htmlspecialchars($c["description"]) ?></td>
        <td><?= htmlspecialchars($c["user_name"] ?? "-") ?></td>
        <td><?= htmlspecialchars($c["apartment_name"] ?? "-") ?></td>
        <td><?= htmlspecialchars($c["flat_no"] ?? "-") ?></td>
        <td>
          <?php if ($c["status"] === "Çözüldü"): ?>
            <span class="badge bg-success">Çözüldü</span>
          <?php else: ?>
            <span class="badge bg-warning text-dark">Beklemede</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</div>
</body>
</html>
