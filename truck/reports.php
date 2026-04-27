<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<style>
 .reports-page {
        min-height: 100vh;
        background: url('trucks1.jpg') center/cover no-repeat fixed;
        margin: -32px;
        padding: 40px 32px;
        position: relative;
    }

    .reports-page::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    .reports-page > * {
        position: relative;
        z-index: 1;
    }
    </style>

<div class="reports-page">
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">


    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="background: linear-gradient(135deg,  #000080); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-size: 2.5rem; margin: 0 0 10px;">
            Reports
        </h1>
        <p style="color: White; font-size: 15px; margin: 0;">Overview of trips, trucks, drivers and customers</p>
    </div>

    
    <div style="margin-bottom: 50px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <span style="font-size: 20px;">🚛</span>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Active Trips</h2>
            <span style="margin-left: auto; background: #ede9fe; color: #000080; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 30px; letter-spacing: 0.5px;">LIVE</span>
        </div>

        <div style="background: white; border-radius: 16px; overflow-x: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">ID</th>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Origin</th>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Destination</th>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Driver</th>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Truck</th>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Customer</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->query("
                    SELECT t.id, t.origin, t.destination,
                           COALESCE(d.name,'None') AS driver_name,
                           COALESCE(tr.registration_number,'None') AS registration_number,
                           COALESCE(c.name,'None') AS customer_name
                    FROM trips t
                    LEFT JOIN drivers d ON t.driver_id = d.id
                    LEFT JOIN trucks tr ON t.truck_id = tr.id
                    LEFT JOIN customers c ON t.customer_id = c.id
                    WHERE t.status IN ('pending','ongoing')
                ");
                while ($trip = $stmt->fetch()):
                ?>
                <tr style="border-top: 1px solid #f1f5f9; text-align: center;"
                    onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='white'">
                    <td style="padding: 16px 20px; font-weight: 600; color: #000080;">#<?= htmlspecialchars($trip['id']) ?></td>
                    <td style="padding: 16px 20px; color: #1e293b;"><?= htmlspecialchars($trip['origin']) ?></td>
                    <td style="padding: 16px 20px; color: #1e293b;"><?= htmlspecialchars($trip['destination']) ?></td>
                    <td style="padding: 16px 20px; color: #475569;"><?= htmlspecialchars($trip['driver_name']) ?></td>
                    <td style="padding: 16px 20px;">
                        <span style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 600;">
                            <?= htmlspecialchars($trip['registration_number']) ?>
                        </span>
                    </td>
                    <td style="padding: 16px 20px; color: #475569;"><?= htmlspecialchars($trip['customer_name']) ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 50px;">

        <!-- Truck Usage -->
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <span style="font-size: 20px;">🔧</span>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Truck Usage</h2>
            </div>
            <div style="background: white; border-radius: 16px; overflow-x: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Truck</th>
                            <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Trips</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stmt = $pdo->query("
                        SELECT tr.registration_number, COUNT(t.id) AS trips_count
                        FROM trucks tr
                        LEFT JOIN trips t ON tr.id = t.truck_id
                        GROUP BY tr.id
                    ");
                    while ($row = $stmt->fetch()):
                    ?>
                    <tr style="border-top: 1px solid #f1f5f9; text-align: center;"
                        onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='white'">
                        <td style="padding: 16px 20px;">
                            <span style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 600;">
                                <?= htmlspecialchars($row['registration_number']) ?>
                            </span>
                        </td>
                        <td style="padding: 16px 20px; font-weight: 700; color: #000080;"><?= htmlspecialchars($row['trips_count']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Driver Workload -->
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <span style="font-size: 20px;">👤</span>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Driver Workload</h2>
            </div>
            <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Driver</th>
                            <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Trips</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stmt = $pdo->query("
                        SELECT d.name, COUNT(t.id) AS trips_count
                        FROM drivers d
                        LEFT JOIN trips t ON d.id = t.driver_id
                        GROUP BY d.id
                    ");
                    while ($row = $stmt->fetch()):
                    ?>
                    <tr style="border-top: 1px solid #f1f5f9; text-align: center;"
                        onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='white'">
                        <td style="padding: 16px 20px; color: #1e293b; font-weight: 500;"><?= htmlspecialchars($row['name']) ?></td>
                        <td style="padding: 16px 20px; font-weight: 700; color: #10b981;"><?= htmlspecialchars($row['trips_count']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Customer Orders Summary -->
    <div style="margin-bottom: 50px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <span style="font-size: 20px;">📦</span>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Customer Orders Summary</h2>
        </div>

        <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.08);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Customer</th>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Trips</th>
                        <th style="padding: 16px 20px; text-align: center; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px;">Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->query("
                    SELECT c.name, COUNT(t.id) AS trips_count,
                           COALESCE(SUM(t.trip_cost),0) AS total_cost
                    FROM customers c
                    LEFT JOIN trips t ON c.id = t.customer_id
                    GROUP BY c.id
                ");
                while ($row = $stmt->fetch()):
                ?>
                <tr style="border-top: 1px solid #f1f5f9; text-align: center;"
                    onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='white'">
                    <td style="padding: 16px 20px; font-weight: 500; color: #1e293b;"><?= htmlspecialchars($row['name']) ?></td>
                    <td style="padding: 16px 20px; font-weight: 700; color: #000080;"><?= htmlspecialchars($row['trips_count']) ?></td>
                    <td style="padding: 16px 20px; font-weight: 700; color: #10b981;">
                        KES <?= number_format($row['total_cost'], 2) ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>
</div>