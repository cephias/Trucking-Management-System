<?php
include 'db.php';
include 'header.php'; 
?>

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
</style>

<div class="trucks-page">
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 40px; gap: 20px;">
        <h1 style="background: linear-gradient(135deg, #667eea, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0;">
            Truck Management
        </h1>
        <a href="add_truck.php" style="background: #10b981; color: white; padding: 14px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: transform 0.2s; box-shadow: 0 4px 20px rgba(16,185,129,0.35);"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            ➕ Add New Truck
        </a>
    </div>

    <?php
    $total_trucks = $pdo->query("SELECT COUNT(*) FROM trucks")->fetchColumn();
    $available    = $pdo->query("SELECT COUNT(*) FROM trucks WHERE status = 'available'")->fetchColumn();
    $in_use       = $pdo->query("SELECT COUNT(*) FROM trucks WHERE status = 'in_use'")->fetchColumn();
    ?>
    
    <!-- Stats cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 50px; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #667eea;">
            <div style="font-size: 32px; font-weight: 800; color: #667eea;"><?= $total_trucks ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Trucks</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #10b981;">
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?= $available ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Available</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #f59e0b;">
            <div style="font-size: 32px; font-weight: 800; color: #f59e0b;"><?= $in_use ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">In Use</div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; overflow-x: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">ID</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Registration</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Model</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Capacity</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Status</th>
                    <th style="padding: 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $status_styles = [
                    'available'   => 'background: #dcfce7; color: #166534;',
                    'in_use'      => 'background: #dbeafe; color: #1e40af;',
                    'maintenance' => 'background: #fef9c3; color: #854d0e;',
                ];

                $stmt = $pdo->query("SELECT * FROM trucks ORDER BY id DESC");
                while ($truck = $stmt->fetch()):
                    $id       = htmlspecialchars($truck['id'], ENT_QUOTES, 'UTF-8');
                    $reg      = htmlspecialchars($truck['registration_number'], ENT_QUOTES, 'UTF-8');
                    $model    = htmlspecialchars($truck['model'], ENT_QUOTES, 'UTF-8');
                    $capacity = number_format((float)$truck['capacity'], 1);
                    $status   = $truck['status'];
                    $badge    = $status_styles[$status] ?? 'background: #f1f5f9; color: #475569;';
                ?>
                <tr style="border-top: 1px solid #f1f5f9; text-align: center;"
                    onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='white'">
                    <td style="padding: 20px; font-weight: 600; color: #000080;">#<?= $id ?></td>
                    <td style="padding: 20px; font-weight: 500; color: #1e293b;"><?= $reg ?></td>
                    <td style="padding: 20px; color: #475569;"><?= $model ?: '<span style="color:#cbd5e1;">—</span>' ?></td>
                    <td style="padding: 20px;">
                        <span style="background: #f0fdf4; color: #166534; padding: 5px 14px; border-radius: 30px; font-size: 13px; font-weight: 700;">
                            <?= $capacity ?> t
                        </span>
                    </td>
                    <td style="padding: 20px;">
                        <span style="padding: 6px 16px; border-radius: 30px; font-size: 12px; font-weight: 700; <?= $badge ?>">
                            <?= strtoupper(str_replace('_', ' ', $status)) ?>
                        </span>
                    </td>
                    <td style="padding: 20px;">
                        <div style="display: flex; justify-content: center; gap: 15px;">
                            <a href="edit_truck.php?id=<?= $id ?>" style="color: #3b82f6; text-decoration: none; font-weight: 500;">Edit</a>
                            <a href="delete_truck.php?id=<?= $id ?>" style="color: #ef4444; text-decoration: none; font-weight: 500;"
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

