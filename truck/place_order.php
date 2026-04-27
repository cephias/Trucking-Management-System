<?php include 'db.php'; ?>

<?php
$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name   = trim($_POST['full_name']);
    $origin      = trim($_POST['origin']);
    $destination = trim($_POST['destination']);
    $cargo       = trim($_POST['cargo_details']);
    $customer_id = $_POST['customer_id'] ?: null;
    $contact     = trim($_POST['contact']);
    $company     = trim($_POST['company_name']);

    if (!$full_name || !$origin || !$destination || !$contact) {
        $error = 'Please fill in all required fields.';
    } else {
        if (!$customer_id) {
            $check = $pdo->prepare("SELECT id FROM customers WHERE contact = ?");
            $check->execute([$contact]);
            $existing = $check->fetchColumn();

            if ($existing) {
                $customer_id = $existing;
                $pdo->prepare("UPDATE customers SET name = ? WHERE id = ? AND name = contact")
                    ->execute([$full_name, $customer_id]);
            } else {
                $ins = $pdo->prepare("INSERT INTO customers (name, contact, company_name, balance) VALUES (?, ?, ?, 0)");
                $ins->execute([$full_name, $contact, $company ?: null]);
                $customer_id = $pdo->lastInsertId();
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO trips (origin, destination, cargo_details, trip_cost, driver_id, truck_id, customer_id, status)
            VALUES (?, ?, ?, 0, NULL, NULL, ?, 'pending')
        ");
        $stmt->execute([$origin, $destination, $cargo, $customer_id]);
        $success = true;
    }
}
?>

<?php include 'header.php'; ?>

<style>
   .dashboard-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .dashboard-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .dashboard-page > * {
        position: relative;
        z-index: 1;
    }
    .order-wrap {
        font-family: 'DM Sans', sans-serif;
        max-width: 760px;
        margin: 0 auto;
        padding: 40px 24px;
    }

    .order-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .order-header h1 {
        font-family: 'Syne', sans-serif;
        font-size: 2.4rem;
        font-weight: 800;
        background: linear-gradient(135deg, #AFEEEE, #000080);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0 0 8px;
    }

    .order-header p {
        color: #fef2f2;
        font-size: 15px;
        margin: 0;
    }

    .success-card {
        background: linear-gradient(135deg, #0f172a, #1e1b4b);
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        color: white;
        animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes popIn {
        from { transform: scale(0.85); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }

    .success-icon {
        font-size: 72px;
        display: block;
        margin: 0 auto 24px;
        animation: floatIcon 3s ease-in-out infinite;
    }

    @keyframes floatIcon {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .success-card h2 {
        font-family: 'Syne', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        margin: 0 0 12px;
    }

    .success-card p {
        color: rgba(255,255,255,0.6);
        font-size: 16px;
        max-width: 400px;
        margin: 0 auto 32px;
        line-height: 1.6;
    }

    .success-actions {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .form-card {
        background: white;
        border-radius: 24px;
        padding: 48px 44px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        border: 1px solid #f1f5f9;
    }

    .error-banner {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 4px solid #ef4444;
        color: #dc2626;
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 28px;
        font-size: 14px;
        font-weight: 500;
    }

    .section-label {
        font-family: 'Syne', sans-serif;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #c4b5fd;
        margin: 32px 0 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }

    .req { color: #ef4444; margin-left: 2px; }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        font-family: 'DM Sans', sans-serif;
        color: #1e293b;
        background: #f8fafc;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 110px;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 4px rgba(129,140,248,0.15);
        background: white;
    }

    .hint {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 5px;
    }

    .divider {
        height: 1px;
        background: #f1f5f9;
        margin: 32px 0;
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #AFEEEE, #000080);
        color: white;
        border: none;
        border-radius: 14px;
        font-family: 'Syne', sans-serif;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.25s, box-shadow 0.25s;
        box-shadow: 0 8px 24px rgba(102,126,234,0.35);
        letter-spacing: 0.3px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(102,126,234,0.45);
    }

    .btn-back {
        display: block;
        text-align: center;
        margin-top: 16px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s;
    }

    .btn-back:hover { color: #475569; }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        font-size: 15px;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.5);
        text-decoration: none;
        color: white;
    }

    .btn-solid {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #AFEEEE, #000080);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: 15px;
        box-shadow: 0 8px 24px rgba(102,126,234,0.4);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-solid:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 32px rgba(102,126,234,0.5);
        text-decoration: none;
        color: white;
    }

    .notice-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-left: 4px solid #22c55e;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 13px;
        color: #166534;
        margin-top: 20px;
        line-height: 1.6;
    }

    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
        .form-card { padding: 32px 24px; }
    }
</style>

<div class="dashboard-page">
<div class="order-wrap">

    <div class="order-header">
        <h1>Place a Shipping Order</h1>
        <p>Tell us where you're sending and we'll get a truck assigned for you</p>
    </div>

    <?php if ($success): ?>

    <div class="success-card">
        <span class="success-icon">🎉🥳🎊</span>
        <h2>Order Submitted!</h2>
        <p>Your shipping request has been received. Our team will review and assign a truck and driver shortly. We'll contact you to confirm details.</p>
        <div class="success-actions">
            <a href="place_order.php" class="btn-solid">Place Another Order</a>
            <a href="customer_dashboard.php" class="btn-outline">⬅️ Back to Home</a>
        </div>
    </div>

    <?php else: ?>

    <div class="form-card">

        <?php if ($error): ?>
            <div class="error-banner">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">

            <!-- Route -->
            <p class="section-label">Route Details</p>
            <div class="form-row">
                <div class="form-group">
                    <label>Origin <span class="req">*</span></label>
                    <input type="text" name="origin" placeholder="e.g. Nairobi Port"
                           value="<?= htmlspecialchars($_POST['origin'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Destination <span class="req">*</span></label>
                    <input type="text" name="destination" placeholder="e.g. Mombasa Port"
                           value="<?= htmlspecialchars($_POST['destination'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Cargo -->
            <p class="section-label">Cargo Information</p>
            <div class="form-group">
                <label>Cargo Description</label>
                <textarea name="cargo_details" placeholder="Describe what you're shipping; type, quantity, special handling needs..."><?= htmlspecialchars($_POST['cargo_details'] ?? '') ?></textarea>
            </div>

            <!-- Your Details -->
            <p class="section-label">👤 Your Details</p>
            <div class="form-group">
                <label>Full Name <span class="req">*</span></label>
                <input type="text" name="full_name" placeholder="e.g. Jane Wanjiru"
                       value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Contact / Phone <span class="req">*</span></label>
                    <input type="text" name="contact" placeholder="e.g. 0712 345 678"
                           value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>" required>
                    <p class="hint">We'll call you to confirm the trip cost.</p>
                </div>
                <div class="form-group">
                    <label>Company Name <span style="color:#cbd5e1; font-size:11px;">(optional)</span></label>
                    <input type="text" name="company_name" placeholder="e.g. Acme Logistics"
                           value="<?= htmlspecialchars($_POST['company_name'] ?? '') ?>">
                </div>
            </div>

            <input type="hidden" name="customer_id" value="">

            <div class="divider"></div>

            <div class="notice-box">
                ℹ️ <strong>How it works:</strong> Once you submit, our team reviews your request, assigns a driver and truck then contacts you with the confirmed cost and pickup time. No payment is needed at this stage.
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn-submit">Submit Shipping Request ✅</button>
            <a href="customer_dashboard.php" class="btn-back">⬅️ Back to Home</a>

        </form>
    </div>

    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>
</div>