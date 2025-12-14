<?php
$host = "sql7.freesqldatabase.com";
$dbname = "sql7811848";
$username = "sql7811848";
$password = "ZvttFN7VhA"; // buraya maildeki şifre

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
