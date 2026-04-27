
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM trips WHERE id = ?");
$stmt->execute([$id]);
header('Location: trips.php');
exit;
?>

<?php include 'header.php'; ?>
