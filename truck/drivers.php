<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

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
</style>

<div class="drivers-page">
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">

    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="background: linear-gradient(135deg, #667eea, #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0 0 30px;">
            Driver Management
        </h1>
    </div>

    <?php
    $total_drivers  = $pdo->query("SELECT COUNT(*) FROM drivers")->fetchColumn();
    $active_drivers = $pdo->query("SELECT COUNT(*) FROM drivers WHERE status = 'active'")->fetchColumn();
    $off_duty       = $pdo->query("SELECT COUNT(*) FROM drivers WHERE status != 'active'")->fetchColumn();
    ?>

    <div style="display: flex; align-items: stretch; gap: 25px; margin-bottom: 50px;">
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #667eea; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #667eea;"><?php echo $total_drivers; ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Total Drivers</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #10b981; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #10b981;"><?php echo $active_drivers; ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Active</div>
        </div>
        <div style="background: white; padding: 30px; border-radius: 16px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #f59e0b; flex: 1;">
            <div style="font-size: 32px; font-weight: 800; color: #f59e0b;"><?php echo $off_duty; ?></div>
            <div style="color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Off Duty</div>
        </div>
        <a href="add_driver.php" style="background: #10b981; color: white; padding: 30px 28px; border-radius: 16px; text-decoration: none; font-weight: 600; font-size: 15px; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-bottom: 4px solid #059669; white-space: nowrap; transition: transform 0.2s;"
           onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            ➕ Add New Driver
        </a>
    </div>

    <div style="background: white; border-radius: 16px; overflow-x: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="padding: 20px; text-align: center; color: #475569;">ID</th>
                    <th style="padding: 20px; text-align: center; color: #475569;">Name</th>
                    <th style="padding: 20px; text-align: center; color: #475569;">License Number</th>
                    <th style="padding: 20px; text-align: center; color: #475569;">Contact</th>
                    <th style="padding: 20px; text-align: center; color: #475569;">Assigned Truck</th>
                    <th style="padding: 20px; text-align: center; color: #475569;">Status</th>
                    <th style="padding: 20px; text-align: center; color: #475569;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $badge_styles = [
                    'active'    => 'background: #dcfce7; color: #166534;',
                    'off_duty'  => 'background: #fef9c3; color: #854d0e;',
                    'suspended' => 'background: #fee2e2; color: #991b1b;',
                ];

                try {
                    // Pull both registration_number AND model so there's more info to show
                    $stmt = $pdo->query("
                        SELECT d.*,
                               t.registration_number,
                               t.model AS truck_model,
                               t.id    AS truck_id
                        FROM drivers d
                        LEFT JOIN trucks t ON d.assigned_truck_id = t.id
                        ORDER BY d.id ASC
                    ");

                    $rows = $stmt->fetchAll();

                    if (empty($rows)) {
                        echo "<tr><td colspan='7' style='padding: 40px; text-align: center; color: #94a3b8;'>No drivers found.</td></tr>";
                    }

                    foreach ($rows as $driver) {
                        $id      = htmlspecialchars($driver['id'], ENT_QUOTES, 'UTF-8');
                        $name    = htmlspecialchars($driver['name'], ENT_QUOTES, 'UTF-8');
                        $license = htmlspecialchars($driver['license_number'], ENT_QUOTES, 'UTF-8');
                        $contact = htmlspecialchars($driver['contact'], ENT_QUOTES, 'UTF-8');
                        $status  = htmlspecialchars($driver['status'], ENT_QUOTES, 'UTF-8');
                        $badge   = $badge_styles[$status] ?? 'background: #f1f5f9; color: #475569;';

                        // Build truck cell, show reg number + model if assigned
                        if ($driver['assigned_truck_id'] && $driver['registration_number']) {
                            $reg   = htmlspecialchars($driver['registration_number'], ENT_QUOTES, 'UTF-8');
                            $model = $driver['truck_model'] ? htmlspecialchars($driver['truck_model'], ENT_QUOTES, 'UTF-8') : '';
                            $label = $model ? "{$reg}  {$model}" : $reg;
                            $truck = "<span style='padding: 6px 16px; border-radius: 30px; font-size: 12px; font-weight: 600;
                                            background: #ede9fe; color: #5b21b6;'> {$label}</span>";
                        } else {
                            $truck = "<span style='padding: 6px 16px; border-radius: 30px; font-size: 12px; font-weight: 600;
                                            background: #f1f5f9; color: #94a3b8;'>Unassigned</span>";
                        }

                        echo "
                        <tr style='border-top: 1px solid #f1f5f9; text-align: center;'
                            onmouseover=\"this.style.background='#fafbff'\"
                            onmouseout=\"this.style.background='white'\">
                            <td style='padding: 20px; font-weight: 600; color: #000080;'>#{$id}</td>
                            <td style='padding: 20px; font-weight: 500; color: #1e293b;'>{$name}</td>
                            <td style='padding: 20px; color: #475569;'>{$license}</td>
                            <td style='padding: 20px; color: #475569;'>{$contact}</td>
                            <td style='padding: 20px;'>{$truck}</td>
                            <td style='padding: 20px;'>
                                <span style='padding: 6px 16px; border-radius: 30px; font-size: 12px; font-weight: 700; {$badge}'>
                                    " . strtoupper(str_replace('_', ' ', $status)) . "
                                </span>
                            </td>
                            <td style='padding: 20px;'>
                                <div style='display: flex; justify-content: center; gap: 15px;'>
                                    <a href='edit_driver.php?id={$id}' style='color: #3b82f6; text-decoration: none; font-weight: 500;'> Edit</a>
                                    <a href='delete_driver.php?id={$id}' style='color: #ef4444; text-decoration: none; font-weight: 500;'
                                       onclick='return confirm(\"Are you sure you want to delete this driver?\")'> Delete</a>
                                </div>
                            </td>
                        </tr>";
                    }

                } catch (PDOException $e) {
                    echo "<tr><td colspan='7' style='padding: 40px; text-align: center; color: #94a3b8;'>⚠️ Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'footer.php'; ?>
</div>