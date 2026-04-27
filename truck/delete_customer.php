
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
$stmt->execute([$id]);
header('Location: customers.php');
exit;
?>

<?php include 'header.php'; ?>
