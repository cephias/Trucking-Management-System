<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<style>
    .index-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .index-page::before { 
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .index-page > * {
        position: relative;
        z-index: 1;
    }
</style>
<div class="index-page">
<h1 style="margin-bottom: 10px; text-align: center;">Dashboard</h1>
<p style="color: #fff; margin-bottom: 30px; text-align: center;">Welcome, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>

<h2 style="margin-bottom: 20px;">Overview</h2>
<?php
// Get counts
$truck_count = $pdo->query("SELECT COUNT(*) FROM trucks")->fetchColumn();
$driver_count = $pdo->query("SELECT COUNT(*) FROM drivers")->fetchColumn();
$trip_count = $pdo->query("SELECT COUNT(*) FROM trips")->fetchColumn();
$customer_count = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$active_trips = $pdo->query("SELECT COUNT(*) FROM trips WHERE status IN ('pending', 'ongoing')")->fetchColumn();
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
    
    <div style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #3498db;">
        <p style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Trucks</p>
        <p style="margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #3498db;"><?php echo htmlspecialchars($truck_count, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    
    <div style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #2ecc71;">
        <p style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Drivers</p>
        <p style="margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #2ecc71;"><?php echo htmlspecialchars($driver_count, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    
    <div style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #9b59b6;">
        <p style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Trips</p>
        <p style="margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #9b59b6;"><?php echo htmlspecialchars($trip_count, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    
    <div style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #e67e22;">
        <p style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Customers</p>
        <p style="margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #e67e22;"><?php echo htmlspecialchars($customer_count, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    
    <div style="background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #e74c3c;">
        <p style="margin: 0; color: #888; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Active Trips</p>
        <p style="margin: 10px 0 0 0; font-size: 32px; font-weight: bold; color: #e74c3c;"><?php echo htmlspecialchars($active_trips, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    
</div>

<?php include 'footer.php'; ?>
</div>