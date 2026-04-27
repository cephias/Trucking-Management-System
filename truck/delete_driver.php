
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM drivers WHERE id = ?");
$stmt->execute([$id]);
header('Location: drivers.php');
exit;
?>

<?php include 'header.php'; ?>
