<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "kapici") {
    header("Location: login.php");
    exit;
}
?>
<div style="text-align:right; margin-bottom:20px;">
  <a href="logout.php" class="btn btn-outline-secondary">Çıkış</a>
</div>
<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "kapici") {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "apartman_db");
$conn->set_charset("utf8");

// Çözüldü butonu
if (isset($_GET["done"])) {
    $id = intval($_GET["done"]);
    $conn->query("UPDATE complaints SET status='Çözüldü' WHERE id=$id");
}

$complaints = [];

$sql = "
SELECT complaints.id, complaints.description, complaints.status, categories.name AS category
FROM complaints
LEFT JOIN categories ON complaints.category_id = categories.id
WHERE complaints.status = 'Beklemede'
ORDER BY complaints.id DESC
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
    <title>Kapıcı Paneli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f7f4;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        h2 {
            color: #5a7f73;
        }
        .btn-main {
            background-color: #8fb9a8;
            color: white;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<div class="container my-5">

    <h2 class="mb-4">🧹 Kapıcı Paneli – Yapılacak İşler</h2>

    <div class="card p-4">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Şikayet</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $c): ?>
                <tr>
                    <td><?= $c["id"] ?></td>
                    <td><?= $c["category"] ?? "—" ?></td>
                    <td><?= $c["description"] ?></td>
                    <td>
                        <a href="?done=<?= $c["id"] ?>" class="btn btn-main btn-sm">
                            Çözüldü
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
