
<?php include 'db.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin      = $_POST['origin'];
    $destination = $_POST['destination'];
    $cargo       = $_POST['cargo_details'];
    $cost        = $_POST['trip_cost'];
    $driver_id   = $_POST['driver_id'] ?: null;
    $truck_id    = $_POST['truck_id'] ?: null;
    $customer_id = $_POST['customer_id'] ?: null;
    $status      = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO trips (origin, destination, cargo_details, trip_cost, driver_id, truck_id, customer_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$origin, $destination, $cargo, $cost, $driver_id, $truck_id, $customer_id, $status]);
    header('Location: trips.php');
    exit;
}
?>

<?php include 'header.php'; ?>

<style>
     .trip-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .trip-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .trip-page > * {
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
    .form-group select,
    .form-group textarea {
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
        font-family: inherit;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #AFEEEE;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        background: white;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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
        background: linear-gradient(135deg, #AFEEEE, #000080);
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

    .section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #cbd5e1;
        margin: 28px 0 16px;
    }
</style>

<div class="trip-page">
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    <!-- Page Header -->
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 40px; gap: 12px;">
        <h1 style="background: linear-gradient(135deg, #AFEEEE, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0;">
            Add New Trip
        </h1>
        <p style="color: #94a3b8; font-size: 15px; margin: 0;">Fill in the details below to create a new trip</p>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST">

            <!-- Route -->
            <p class="section-label">Route</p>
            <div class="form-row">
                <div class="form-group">
                    <label for="origin">Origin</label>
                    <input type="text" id="origin" name="origin" placeholder="e.g. Nairobi" required>
                </div>
                <div class="form-group">
                    <label for="destination">Destination</label>
                    <input type="text" id="destination" name="destination" placeholder="e.g. Mombasa" required>
                </div>
            </div>

            <!-- Cargo & Cost -->
            <p class="section-label">Cargo & Cost</p>
            <div class="form-group">
                <label for="cargo_details">Cargo Details</label>
                <textarea id="cargo_details" name="cargo_details" placeholder="Describe the cargo"></textarea>
            </div>
            <div class="form-group">
                <label for="trip_cost">Trip Cost (KES)</label>
                <input type="number" step="0.01" id="trip_cost" name="trip_cost" placeholder="e.g. 15000.00" required>
            </div>

            <!-- Assignments -->
            <p class="section-label">👤 Assignments</p>
            <div class="form-row">
                <div class="form-group">
                    <label for="driver_id">Driver</label>
                    <div class="select-wrapper">
                        <select id="driver_id" name="driver_id">
                            <option value="">None</option>
                            <?php
                            $drivers = $pdo->query("SELECT id, name FROM drivers WHERE status = 'active'");
                            while ($driver = $drivers->fetch()):
                            ?>
                            <option value="<?= $driver['id'] ?>"><?= htmlspecialchars($driver['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="truck_id">Truck</label>
                    <div class="select-wrapper">
                        <select id="truck_id" name="truck_id">
                            <option value="">None</option>
                            <?php
                            $trucks = $pdo->query("SELECT id, registration_number FROM trucks WHERE status = 'available'");
                            while ($truck = $trucks->fetch()):
                            ?>
                            <option value="<?= $truck['id'] ?>"><?= htmlspecialchars($truck['registration_number']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <div class="select-wrapper">
                        <select id="customer_id" name="customer_id">
                            <option value="">None</option>
                            <?php
                            $customers = $pdo->query("SELECT id, name FROM customers");
                            while ($customer = $customers->fetch()):
                            ?>
                            <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <div class="select-wrapper">
                        <select id="status" name="status">
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn-submit">➕ Add Trip</button>
            <a href="trips.php" class="btn-cancel">⬅️ Back to Trip Management</a>

        </form>
    </div>

</div>


<?php include 'footer.php'; ?>
</div>