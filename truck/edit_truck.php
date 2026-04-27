<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM trucks WHERE id = ?");
$stmt->execute([$id]);
$truck = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_num  = $_POST['registration_number'];
    $model    = $_POST['model'];
    $capacity = $_POST['capacity'];
    $status   = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE trucks SET registration_number = ?, model = ?, capacity = ?, status = ? WHERE id = ?");
    $stmt->execute([$reg_num, $model, $capacity, $status, $id]);
    header('Location: trucks.php');
    exit;
}
?>

<?php include 'header.php'; ?>

<style>
    .trucks-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .trucks-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .trucks-page > * {
        position: relative;
        z-index: 1;
    }
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        max-width: 600px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        background: white;
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper::after {
        content: '▾';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        font-size: 14px;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea, #000080);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-top: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(102,126,234,0.4);
    }

    .btn-cancel {
        display: block;
        text-align: center;
        margin-top: 16px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s;
    }

    .btn-cancel:hover {
        color: #475569;
    }

    .divider {
        height: 1px;
        background: #f1f5f9;
        margin: 28px 0;
    }

    .truck-id-badge {
        display: inline-block;
        background: #f1f5f9;
        color: #000080;
        font-size: 13px;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 30px;
        letter-spacing: 0.5px;
    }
</style>

<div class="trucks-page">
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    <!-- Page Header -->
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 40px; gap: 12px;">
        <h1 style="background: linear-gradient(135deg, #667eea, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0;">
            Edit Truck
        </h1>
        <span class="truck-id-badge">Truck #<?php echo $truck['id']; ?>  <?php echo htmlspecialchars($truck['registration_number']); ?></span>
    </div>

    <!-- Form Card -->
    <div class="form-card">

        <form method="POST">

            <div class="form-group">
                <label for="registration_number">Registration Number</label>
                <input type="text" id="registration_number" name="registration_number"
                       value="<?php echo htmlspecialchars($truck['registration_number']); ?>" required>
            </div>

            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" id="model" name="model"
                       value="<?php echo htmlspecialchars($truck['model']); ?>" required>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity (tons)</label>
                <input type="number" step="0.1" id="capacity" name="capacity"
                       value="<?php echo htmlspecialchars($truck['capacity']); ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <div class="select-wrapper">
                    <select id="status" name="status">
                        <option value="available"    <?php if ($truck['status'] == 'available')    echo 'selected'; ?>>Available</option>
                        <option value="in_use"       <?php if ($truck['status'] == 'in_use')       echo 'selected'; ?>>In Use</option>
                        <option value="maintenance"  <?php if ($truck['status'] == 'maintenance')  echo 'selected'; ?>>Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn-submit">Save Changes</button>
            <a href="trucks.php" class="btn-cancel">⬅️Back to Truck Management</a>

        </form>

    </div>
</div>

<?php include 'footer.php'; ?>
</div>