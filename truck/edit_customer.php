
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$id]);
$customer = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $_POST['name'];
    $contact = $_POST['contact'];
    $company = $_POST['company_name'];
    $balance = $_POST['balance'];

    $stmt = $pdo->prepare("UPDATE customers SET name = ?, contact = ?, company_name = ?, balance = ? WHERE id = ?");
    $stmt->execute([$name, $contact, $company, $balance, $id]);
    header('Location: customers.php');
    exit;
}
?>

<?php include 'header.php'; ?>

<style>
    .customers-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .customers-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .customers-page > * {
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

    .form-group input {
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
        font-family: inherit;
    }

    .form-group input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        background: white;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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

    .btn-cancel:hover { color: #475569; }

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

<div class="customers-page">
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    <!-- Page Header -->
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 40px; gap: 12px;">
        <h1 style="background: linear-gradient(135deg, #667eea, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0;">
            Edit Customer
        </h1>
        <span style="display: inline-block; background: #f1f5f9; color: #000080; font-size: 13px; font-weight: 700; padding: 4px 14px; border-radius: 30px; letter-spacing: 0.5px;">
            Customer #<?= $customer['id'] ?> | <?= htmlspecialchars($customer['name']) ?>
        </span>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST">

            <!-- Customer Info -->
            <p class="section-label">👤 Customer Info</p>
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name"
                           value="<?= htmlspecialchars($customer['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact"
                           value="<?= htmlspecialchars($customer['contact']) ?>" required>
                </div>
            </div>

            <!-- Company & Balance -->
            <p class="section-label">🏢 Company & Billing</p>
            <div class="form-row">
                <div class="form-group">
                    <label for="company_name">Company Name <span style="color:#cbd5e1; font-size:11px;">(optional)</span></label>
                    <input type="text" id="company_name" name="company_name"
                           value="<?= htmlspecialchars($customer['company_name']) ?>">
                </div>
                <div class="form-group">
                    <label for="balance">Balance (KES)</label>
                    <input type="number" step="0.01" id="balance" name="balance"
                           value="<?= htmlspecialchars($customer['balance']) ?>">
                </div>
            </div>

            <div class="divider"></div>

            <button type="submit" class="btn-submit">💾 Save Changes</button>
            <a href="customers.php" class="btn-cancel">⬅️ Back to Customer Management</a>

        </form>
    </div>

</div>


<?php include 'footer.php'; ?>
</div>