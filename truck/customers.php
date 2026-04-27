<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

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
</style>

<div class="customers-page">
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    <?php
    $total      = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
    $total_bal  = $pdo->query("SELECT COALESCE(SUM(balance), 0) FROM customers")->fetchColumn();
    $positive   = $pdo->query("SELECT COUNT(*) FROM customers WHERE balance > 0")->fetchColumn();
    ?>

    <!-- Page Header -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="background: linear-gradient(135deg, #667eea, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0 0 30px;">
            Customer Management
        </h1>
    </div>

    <!-- Stats + Add Button -->
    <div style="display: flex; align-items: stretch; gap: 25px; margin-bottom: 50px;">
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #667eea; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #667eea;"><?= $total ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Customers</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #10b981; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?= $positive ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">With Balance</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #f59e0b; flex: 1;">
            <div style="font-size: 28px; font-weight: 800; color: #f59e0b;">KES <?= number_format($total_bal, 0) ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Balance</div>
        </div>
        <a href="add_customer.php" style="background: #10b981; color: white; padding: 30px 28px; border-radius: 16px; text-decoration: none; font-weight: 600; font-size: 15px; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #059669; white-space: nowrap; transition: transform 0.2s;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            ➕ Add New Customer
        </a>
    </div>

    <!-- Table -->
    <div style="background: white; border-radius: 16px; overflow-x: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">ID</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Name</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Contact</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Company</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Balance</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM customers ORDER BY id DESC");
            while ($customer = $stmt->fetch()):
                $id      = htmlspecialchars($customer['id'], ENT_QUOTES, 'UTF-8');
                $name    = htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8');
                $contact = htmlspecialchars($customer['contact'], ENT_QUOTES, 'UTF-8');
                $company = htmlspecialchars($customer['company_name'], ENT_QUOTES, 'UTF-8') ?: '<span style="color:#cbd5e1;">—</span>';
                $balance = $customer['balance'];
                $bal_color = $balance > 0 ? '#10b981' : ($balance < 0 ? '#ef4444' : '#94a3b8');
            ?>
            <tr style="border-top: 1px solid #f1f5f9; text-align: center;"
                onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='white'">
                <td style="padding: 20px; font-weight: 600; color: #000080;">#<?= $id ?></td>
                <td style="padding: 20px; font-weight: 500; color: #1e293b;"><?= $name ?></td>
                <td style="padding: 20px; color: #475569;"><?= $contact ?></td>
                <td style="padding: 20px; color: #475569;"><?= $company ?></td>
                <td style="padding: 20px; font-weight: 700; color: <?= $bal_color ?>;">
                    KES <?= number_format($balance, 2) ?>
                </td>
                <td style="padding: 20px;">
                    <div style="display: flex; justify-content: center; gap: 15px;">
                        <a href="edit_customer.php?id=<?= $id ?>" style="color: #3b82f6; text-decoration: none; font-weight: 500;">Edit</a>
                        <a href="delete_customer.php?id=<?= $id ?>" style="color: #ef4444; text-decoration: none; font-weight: 500;"
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'footer.php'; ?>
</div>