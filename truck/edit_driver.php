<?php include 'db.php'; ?>

<?php
$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM drivers WHERE id = ?");
$stmt->execute([$id]);
$driver = $stmt->fetch();

if (!$driver) {
    header('Location: drivers.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $license  = $_POST['license_number'];
    $contact  = $_POST['contact'];
    $truck_id = !empty($_POST['assigned_truck_id']) ? (int) $_POST['assigned_truck_id'] : null;
    $status   = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE drivers SET name = ?, license_number = ?, contact = ?, assigned_truck_id = ?, status = ? WHERE id = ?");
    $stmt->execute([$name, $license, $contact, $truck_id, $status, $id]);
    header('Location: drivers.php');
    exit;
}
?>

<?php include 'header.php'; ?>

<style>
    .drivers-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .drivers-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .drivers-page > * {
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

    .truck-badge {
        display: inline-block;
        background: #ede9fe;
        color: #5b21b6;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 30px;
        margin-left: 8px;
    }
</style>

<div class="drivers-page">
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    <!-- Page Header -->
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 40px; gap: 12px;">
        <h1 style="background: linear-gradient(135deg, #667eea, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0;">
            Edit Driver
        </h1>
        <span style="display: inline-block; background: #f1f5f9; color: #667eea; font-size: 13px; font-weight: 700; padding: 4px 14px; border-radius: 30px; letter-spacing: 0.5px;">
            Driver #<?php echo htmlspecialchars($driver['id'], ENT_QUOTES, 'UTF-8'); ?>  <?php echo htmlspecialchars($driver['name'], ENT_QUOTES, 'UTF-8'); ?>
        </span>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                       value="<?php echo htmlspecialchars($driver['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="license_number">License Number</label>
                <input type="text" id="license_number" name="license_number"
                       value="<?php echo htmlspecialchars($driver['license_number'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" id="contact" name="contact"
                       value="<?php echo htmlspecialchars($driver['contact'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group">
                <label for="assigned_truck_id">Assigned Truck</label>
                <div class="select-wrapper">
                    <select id="assigned_truck_id" name="assigned_truck_id">
                        <option value=""> None </option>
                        <?php
                       
                        // Shows all available trucks + the currently assigned truck 
                        if (!empty($driver['assigned_truck_id'])) {
                            $trucks = $pdo->prepare("
                                SELECT id, registration_number, model
                                FROM trucks
                                WHERE status = 'available' OR id = ?
                                ORDER BY registration_number
                            ");
                            $trucks->execute([$driver['assigned_truck_id']]);
                        } else {
                            $trucks = $pdo->query("
                                SELECT id, registration_number, model
                                FROM trucks
                                WHERE status = 'available'
                                ORDER BY registration_number
                            ");
                        }

                        while ($truck = $trucks->fetch()) {
                            $selected = ($truck['id'] == $driver['assigned_truck_id']) ? 'selected' : '';
                            $label    = htmlspecialchars($truck['registration_number'], ENT_QUOTES, 'UTF-8');
                            if (!empty($truck['model'])) {
                                $label .= ' | ' . htmlspecialchars($truck['model'], ENT_QUOTES, 'UTF-8');
                            }
                            // Mark the currently assigned truck clearly
                            $current = ($truck['id'] == $driver['assigned_truck_id']) ? '  Current' : '';
                            echo "<option value='{$truck['id']}' {$selected}>{$label}{$current}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <div class="select-wrapper">
                    <select id="status" name="status">
                        <option value="active"    <?php if ($driver['status'] == 'active')    echo 'selected'; ?>>Active</option>
                        <option value="off_duty"  <?php if ($driver['status'] == 'off_duty')  echo 'selected'; ?>>Off Duty</option>
                        <option value="suspended" <?php if ($driver['status'] == 'suspended') echo 'selected'; ?>>Suspended</option>
                        <option value="inactive"  <?php if ($driver['status'] == 'inactive')  echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn-submit">💾 Save Changes</button>
            <a href="drivers.php" class="btn-cancel">⬅️ Back to Driver Management</a>

        </form>
    </div>

</div>

<?php include 'footer.php'; ?>
</div>