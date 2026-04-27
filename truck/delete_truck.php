
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM trucks WHERE id = ?");
$stmt->execute([$id]);
header('Location: trucks.php');
exit;
?>

<?php include 'header.php'; ?>