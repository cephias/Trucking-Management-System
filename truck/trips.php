
<?php include 'db.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign') {

    $trip_id    = (int) $_POST['trip_id'];
    $driver_id  = $_POST['driver_id']  ?: null;
    $truck_id   = $_POST['truck_id']   ?: null;
    $new_cost   = (float) $_POST['trip_cost'];
    $new_status = $_POST['status'];

    // Fetch old trip to calculate balance 
    $old = $pdo->prepare("SELECT trip_cost, customer_id FROM trips WHERE id = ?");
    $old->execute([$trip_id]);
    $old_trip = $old->fetch();

    $old_cost    = (float) ($old_trip['trip_cost'] ?? 0);
    $customer_id = $old_trip['customer_id'] ?? null;
    $cost_delta  = $new_cost - $old_cost;

    // Update the trip
    $upd = $pdo->prepare("
        UPDATE trips
        SET driver_id  = ?,
            truck_id   = ?,
            trip_cost  = ?,
            status     = ?
        WHERE id = ?
    ");
    $upd->execute([$driver_id, $truck_id, $new_cost, $new_status, $trip_id]);

    // Adjust customer balance if cost changed and customer exists
    if ($customer_id && $cost_delta != 0) {
        $bal = $pdo->prepare("UPDATE customers SET balance = balance + ? WHERE id = ?");
        $bal->execute([$cost_delta, $customer_id]);
    }

    header('Location: trips.php?assigned=1');
    exit;
}
?>

<?php include 'header.php'; ?>

<style>
    .trips-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .trips-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .trips-page > * {
        position: relative;
        z-index: 1;
    }
</style>

<div class="trips-page">
<?php
// Pull data for the modal dropdowns
$available_drivers = $pdo->query("SELECT id, name FROM drivers WHERE status = 'active' ORDER BY name")->fetchAll();
$available_trucks  = $pdo->query("SELECT id, registration_number, model FROM trucks WHERE status = 'available' ORDER BY registration_number")->fetchAll();
?>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    <?php
    $total     = $pdo->query("SELECT COUNT(*) FROM trips")->fetchColumn();
    $pending   = $pdo->query("SELECT COUNT(*) FROM trips WHERE status = 'pending'")->fetchColumn();
    $ongoing   = $pdo->query("SELECT COUNT(*) FROM trips WHERE status = 'ongoing'")->fetchColumn();
    $completed = $pdo->query("SELECT COUNT(*) FROM trips WHERE status = 'completed'")->fetchColumn();
    ?>

    <?php if (isset($_GET['assigned'])): ?>
    <div id="toast" style="
        position: fixed; top: 28px; right: 28px; z-index: 9999;
        background: #0f172a; color: white;
        padding: 16px 24px; border-radius: 14px;
        font-size: 14px; font-weight: 600;
        box-shadow: 0 12px 32px rgba(0,0,0,0.25);
        display: flex; align-items: center; gap: 10px;
        animation: slideInToast 0.4s ease;
    ">
        ✅ Trip assigned & balance updated!
        <button onclick="document.getElementById('toast').remove()"
                style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px;padding:0;line-height:1;">×</button>
    </div>
    <style>
        @keyframes slideInToast {
            from { opacity:0; transform: translateY(-16px); }
            to   { opacity:1; transform: translateY(0); }
        }
    </style>
    <script>setTimeout(() => { const t = document.getElementById('toast'); if(t) t.remove(); }, 4000);</script>
    <?php endif; ?>

    <!-- Page Header -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="background: linear-gradient(135deg, #AFEEEE, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0 0 30px;">
            Trip Management
        </h1>
    </div>

    <!-- Stats + Add Button -->
    <div style="display: flex; align-items: stretch; gap: 25px; margin-bottom: 50px;">
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #AFEEEE; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #081881;"><?= $total ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Trips</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #f59e0b; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #f59e0b;"><?= $pending ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Pending</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #3b82f6; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #3b82f6;"><?= $ongoing ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Ongoing</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #10b981; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?= $completed ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Completed</div>
        </div>
        <a href="add_trip.php" style="background: #10b981; color: white; padding: 30px 28px; border-radius: 16px; text-decoration: none; font-weight: 600; font-size: 15px; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #059669; white-space: nowrap; transition: transform 0.2s;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            ➕ Add New Trip
        </a>
    </div>

    <!-- Table -->
    <div style="background: white; border-radius: 16px; overflow-x: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">ID</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Route</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Cargo</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Cost</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Driver</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Truck</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Customer</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Status</th>
                    <th style="padding: 14px 12px; text-align: center; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $badge = [
                'pending'   => 'background:#fef9c3; color:#854d0e;',
                'ongoing'   => 'background:#dbeafe; color:#1e40af;',
                'completed' => 'background:#dcfce7; color:#166534;',
                'cancelled' => 'background:#fee2e2; color:#991b1b;',
            ];

            try {
                $stmt = $pdo->query("
                    SELECT t.*, d.name AS driver_name, tr.registration_number,
                           tr.model AS truck_model, c.name AS customer_name
                    FROM trips t
                    LEFT JOIN drivers d  ON t.driver_id  = d.id
                    LEFT JOIN trucks tr  ON t.truck_id   = tr.id
                    LEFT JOIN customers c ON t.customer_id = c.id
                    ORDER BY t.id DESC
                ");
                while ($trip = $stmt->fetch()):
                    $id          = (int) $trip['id'];
                    $origin      = htmlspecialchars($trip['origin'],      ENT_QUOTES, 'UTF-8');
                    $destination = htmlspecialchars($trip['destination'],  ENT_QUOTES, 'UTF-8');
                    $cargo       = htmlspecialchars($trip['cargo_details'],ENT_QUOTES, 'UTF-8');
                    $cost        = number_format($trip['trip_cost'], 2);
                    $driver      = $trip['driver_name']
                                    ? htmlspecialchars($trip['driver_name'], ENT_QUOTES, 'UTF-8')
                                    : '<span style="color:#cbd5e1;">Unassigned</span>';
                    $truckLabel  = $trip['registration_number']
                                    ? htmlspecialchars($trip['registration_number'], ENT_QUOTES, 'UTF-8')
                                    : 'None';
                    $customer    = $trip['customer_name']
                                    ? htmlspecialchars($trip['customer_name'], ENT_QUOTES, 'UTF-8')
                                    : '<span style="color:#cbd5e1;">None</span>';
                    $status      = $trip['status'];
                    $badgeStyle  = $badge[$status] ?? 'background:#f1f5f9; color:#475569;';
                    $needsAssign = !$trip['driver_id'] || !$trip['trip_cost'];
            ?>
                <tr style="border-top: 1px solid #f1f5f9; text-align: center;"
                    onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='white'">
                    <td style="padding: 12px; font-weight: 600; color: #000080;">#<?= $id ?></td>
                    <td style="padding: 12px; color: #1e293b; white-space: nowrap;">
                        <?= $origin ?> <span style="color:#cbd5e1;">➡️</span> <?= $destination ?>
                    </td>
                    <td style="padding: 12px; color: #475569; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $cargo ?: '<span style="color:#cbd5e1;">—</span>' ?></td>
                    <td style="padding: 12px; font-weight: 600; color: <?= $trip['trip_cost'] > 0 ? '#10b981' : '#f59e0b' ?>; white-space: nowrap;">
                        <?= $trip['trip_cost'] > 0 ? 'KES ' . $cost : '<span style="color:#f59e0b; font-size:11px;">Not set</span>' ?>
                    </td>
                    <td style="padding: 12px; color: #475569;"><?= $driver ?></td>
                    <td style="padding: 12px;">
                        <span style="background: #f1f5f9; color: #475569; padding: 3px 10px; border-radius: 30px; font-size: 11px; font-weight: 600;"><?= $truckLabel ?></span>
                    </td>
                    <td style="padding: 12px; color: #475569;"><?= $customer ?></td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 10px; border-radius: 30px; font-size: 11px; font-weight: 700; <?= $badgeStyle ?>">
                            <?= strtoupper($status) ?>
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        <div style="display: flex; justify-content: center; gap: 8px; white-space: nowrap; flex-wrap: wrap;">
                            <!-- Assign button highlighted if unassigned -->
                            <button
                                onclick="openAssign(<?= htmlspecialchars(json_encode([
                                    'id'        => $id,
                                    'driver_id' => $trip['driver_id'],
                                    'truck_id'  => $trip['truck_id'],
                                    'trip_cost' => $trip['trip_cost'],
                                    'status'    => $status,
                                    'origin'    => $trip['origin'],
                                    'destination' => $trip['destination'],
                                    'customer'  => $trip['customer_name'] ?? '',
                                ]), ENT_QUOTES) ?>)"
                                style="
                                    padding: 5px 12px; border-radius: 20px; border: none; cursor: pointer;
                                    font-size: 11px; font-weight: 700; transition: all 0.2s;
                                    <?= $needsAssign
                                        ? 'background: linear-gradient(135deg,#667eea,#764ba2); color:white; box-shadow: 0 3px 10px rgba(102,126,234,0.35);'
                                        : 'background: #ede9fe; color: #5b21b6;' ?>
                                "
                            >⚡ <?= $needsAssign ? 'Assign' : 'Reassign' ?></button>

                            <a href="edit_trip.php?id=<?= $id ?>" style="color: #3b82f6; text-decoration: none; font-weight: 500; font-size: 12px; padding: 5px 4px;">Edit</a>
                            <a href="delete_trip.php?id=<?= $id ?>" style="color: #ef4444; text-decoration: none; font-weight: 500; font-size: 12px; padding: 5px 4px;"
                               onclick="return confirm('Delete this trip?')">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile;
            } catch (PDOException $e) {
                echo "<tr><td colspan='9' style='padding: 40px; text-align: center; color: #94a3b8;'>⚠️ Error loading trips.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>


<div id="assignModal" style="
    display: none;
    position: fixed; inset: 0; z-index: 9000;
    background: rgba(15,23,42,0.55);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 20px;
">
    <div style="
        background: white;
        border-radius: 24px;
        padding: 40px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 32px 80px rgba(0,0,0,0.2);
        animation: modalIn 0.3s cubic-bezier(0.34,1.56,0.64,1);
        position: relative;
    ">
        <style>
            @keyframes modalIn {
                from { transform: scale(0.88) translateY(20px); opacity: 0; }
                to   { transform: scale(1)    translateY(0);    opacity: 1; }
            }
            .modal-label {
                display: block;
                font-size: 12px;
                font-weight: 700;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                margin-bottom: 8px;
            }
            .modal-select, .modal-input {
                width: 100%;
                padding: 12px 16px;
                border: 1.5px solid #e2e8f0;
                border-radius: 12px;
                font-size: 15px;
                color: #1e293b;
                background: #f8fafc;
                box-sizing: border-box;
                outline: none;
                appearance: none;
                -webkit-appearance: none;
                font-family: inherit;
                transition: border-color 0.2s, box-shadow 0.2s;
                margin-bottom: 20px;
            }
            .modal-select:focus, .modal-input:focus {
                border-color: #000080;
                box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
                background: white;
            }
            .select-wrap { position: relative; }
            .select-wrap::after {
                content: '▾';
                position: absolute;
                right: 14px; top: 38%;
                transform: translateY(-50%);
                color: #94a3b8;
                pointer-events: none;
            }
            .cost-preview {
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                border-radius: 12px;
                padding: 12px 16px;
                font-size: 13px;
                color: #166534;
                margin-bottom: 20px;
                display: none;
            }
        </style>

        <!-- Close -->
        <button onclick="closeAssign()" style="
            position: absolute; top: 16px; right: 16px;
            background: #f1f5f9; border: none; border-radius: 50%;
            width: 34px; height: 34px; font-size: 18px; cursor: pointer;
            color: #94a3b8; line-height: 1; display: flex; align-items: center; justify-content: center;
            transition: background 0.2s;
        " onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">×</button>

        <!-- Header -->
        <div style="margin-bottom: 28px;">
            <h2 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin: 0 0 4px;">🚛 Assign Trip</h2>
            <p id="modal-route" style="color: #94a3b8; font-size: 14px; margin: 0;"></p>
        </div>

        <form method="POST">
            <input type="hidden" name="action"  value="assign">
            <input type="hidden" name="trip_id" id="modal-trip-id">

            <!-- Driver -->
            <label class="modal-label">Driver</label>
            <div class="select-wrap">
                <select name="driver_id" id="modal-driver" class="modal-select">
                    <option value="">Driver</option>
                    <?php foreach ($available_drivers as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Truck -->
            <label class="modal-label">Truck</label>
            <div class="select-wrap">
                <select name="truck_id" id="modal-truck" class="modal-select">
                    <option value="">Truck </option>
                    <?php foreach ($available_trucks as $t): ?>
                    <option value="<?= $t['id'] ?>">
                        <?= htmlspecialchars($t['registration_number']) ?>
                        <?= $t['model'] ? ' | ' . htmlspecialchars($t['model']) : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Cost -->
            <label class="modal-label">Trip Cost (KES)</label>
            <input type="number" step="0.01" min="0"
                   name="trip_cost" id="modal-cost" class="modal-input"
                   placeholder="e.g. 25000.00"
                   oninput="previewDelta()">

            <!-- Balance preview -->
            <div class="cost-preview" id="cost-preview"></div>

            <!-- Status -->
            <label class="modal-label">Status</label>
            <div class="select-wrap">
                <select name="status" id="modal-status" class="modal-select">
                    <option value="pending">Pending</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <!-- Customer info line -->
            <div id="modal-customer-line" style="
                background: #f8fafc; border-radius: 10px;
                padding: 10px 14px; font-size: 13px; color: #475569;
                margin-bottom: 24px; display: none;
            "></div>

            <button type="submit" style="
                width: 100%; padding: 15px;
                background: linear-gradient(135deg, #AFEEEE, #000080);
                color: white; border: none; border-radius: 14px;
                font-size: 15px; font-weight: 700; cursor: pointer;
                box-shadow: 0 8px 24px rgba(102,126,234,0.35);
                transition: transform 0.2s, box-shadow 0.2s;
            "
            onmouseover="this.style.transform='translateY(-2px)'"
            onmouseout="this.style.transform='translateY(0)'">
                💾 Save Assignment
            </button>
        </form>
    </div>
</div>

<script>
let currentOldCost = 0;
let currentCustomer = '';

function openAssign(trip) {
    // Populate hidden fields
    document.getElementById('modal-trip-id').value = trip.id;
    document.getElementById('modal-route').textContent =
        trip.origin + ' ➡️ ' + trip.destination;

    // Pre-select current driver
    const driverSel = document.getElementById('modal-driver');
    for (let o of driverSel.options) {
        o.selected = (o.value == trip.driver_id);
    }

    // Pre-select current truck
    const truckSel = document.getElementById('modal-truck');
    for (let o of truckSel.options) {
        o.selected = (o.value == trip.truck_id);
    }

    // Cost
    document.getElementById('modal-cost').value =
        trip.trip_cost > 0 ? parseFloat(trip.trip_cost).toFixed(2) : '';
    currentOldCost = parseFloat(trip.trip_cost) || 0;

    // Status
    const statusSel = document.getElementById('modal-status');
    for (let o of statusSel.options) {
        o.selected = (o.value === trip.status);
    }

    // Customer line
    currentCustomer = trip.customer || '';
    const custLine = document.getElementById('modal-customer-line');
    if (currentCustomer) {
        custLine.style.display = 'block';
        custLine.innerHTML = '👤 Customer: <strong>' + currentCustomer + '</strong>  balance will be adjusted when cost changes.';
    } else {
        custLine.style.display = 'none';
    }

    // Reset preview
    document.getElementById('cost-preview').style.display = 'none';

    // Show modal
    const modal = document.getElementById('assignModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAssign() {
    document.getElementById('assignModal').style.display = 'none';
    document.body.style.overflow = '';
}

function previewDelta() {
    if (!currentCustomer) return;
    const newCost = parseFloat(document.getElementById('modal-cost').value) || 0;
    const delta   = newCost - currentOldCost;
    const preview = document.getElementById('cost-preview');

    if (delta === 0 || currentOldCost === 0 && newCost === 0) {
        preview.style.display = 'none';
        return;
    }

    const sign    = delta > 0 ? '+' : '';
    const absVal  = Math.abs(delta).toLocaleString('en-KE', {minimumFractionDigits: 2});
    const arrow   = delta > 0 ? '⬆️' : '⬇️';
    preview.style.display = 'block';
    preview.innerHTML = arrow + ' Customer <strong>' + currentCustomer + '</strong>\'s balance will change by <strong>'
        + sign + 'KES ' + absVal + '</strong> (new cost: KES '
        + newCost.toLocaleString('en-KE', {minimumFractionDigits: 2}) + ')';
}

// Close modal on backdrop click
document.getElementById('assignModal').addEventListener('click', function(e) {
    if (e.target === this) closeAssign();
});

// ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAssign();
});
</script>

<?php include 'footer.php'; ?>
</div>