<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$admin_only_pages = [
    'trucks.php', 'add_truck.php', 'edit_truck.php', 'delete_truck.php',
    'drivers.php', 'add_driver.php', 'edit_driver.php', 'delete_driver.php',
    'trips.php', 'add_trip.php', 'edit_trip.php', 'delete_trip.php',
    'customers.php', 'add_customer.php', 'edit_customer.php', 'delete_customer.php',
    'reports.php',
    'index.php',
];

$current_page = basename($_SERVER['PHP_SELF']);
$is_customer  = ($_SESSION['role'] === 'customer');

if ($is_customer && in_array($current_page, $admin_only_pages)) {
    header('Location: customer_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kai Trucking Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        nav {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px; 
            height: 100vh;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 2px 0 20px rgba(0,0,0,0.08);
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }

        .nav-header {
            padding: 30px 25px;
            background: linear-gradient(135deg, #AFEEEE 0%, #000080 100%);
            color: white;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .nav-header h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .nav-header p {
            font-size: 12px;
            opacity: 0.9;
        }

        /* Role badge in nav header */
        .role-badge {
            display: inline-block;
            margin-top: 8px;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .role-badge.admin {
            background: rgba(255,255,255,0.25);
            color: white;
        }

        .role-badge.customer {
            background: rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.9);
        }

        nav ul {
            list-style: none;
            padding: 10px 0;
            margin-top: 10px;
        }

        nav li {
            margin: 0;
        }

        nav a {
            display: flex;
            align-items: center;
            padding: 16px 25px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.25s ease;
            border-left: 3px solid transparent;
        }

        nav a:hover {
            background: rgba(102, 126, 234, 0.08);
            color: #000080;
            border-left-color: #AFEEEE;
            transform: translateX(5px);
        }

        nav a.active {
            background: rgba(102, 126, 234, 0.12);
            color: #000080;
            border-left-color: #000080;
            font-weight: 600;
        }

        nav a::before {
            content: '';
            width: 20px;
            height: 20px;
            margin-right: 14px;
            font-size: 18px;
        }

        /* Admin nav icons */
        .dashboard::before  { content: '📊'; }
        .trucks::before     { content: '🚛'; }
        .drivers::before    { content: '👨‍💼'; }
        .trips::before      { content: '🛣️'; }
        .customers::before  { content: '👥'; }
        .reports::before    { content: '📈'; }
        .logout::before     { content: '❌'; }

        /* Customer nav icons */
        .cust-home::before   { content: '🏠'; }
        .cust-order::before  { content: '📦'; }
        .cust-logout::before { content: '❌'; }

        .nav-section-label {
            padding: 18px 25px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #cbd5e1;
        }

        .container {
            margin-left: 260px; 
            padding: 40px;
            min-height: 100vh;
}

        @media (max-width: 1024px) {
            nav { width: 280px; }
            .container { margin-left: 280px; }
        }

        @media (max-width: 768px) {
            nav { transform: translateX(-100%); }
            nav.mobile-open { transform: translateX(0); }
            .container { margin-left: 0; }
            .mobile-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1100;
                background: rgba(255,255,255,0.95);
                border: none;
                padding: 12px 16px;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                cursor: pointer;
                font-size: 18px;
                backdrop-filter: blur(10px);
            }
        }

        .mobile-toggle { display: none; }
    </style>
</head>
<body>

    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

    <nav id="sidebar">
        <div class="nav-header">
            <h1>🚚🚛 Kai Trucking</h1>
            <p>Management System</p>
            <span class="role-badge <?= $is_customer ? 'customer' : 'admin' ?>">
                <?= $is_customer ? '🙋 Customer' : '🔐 Admin' ?>
            </span>
        </div>

        <?php if ($is_customer): ?>
        <!-- CUSTOMER NAVIGATION -->
        <ul>
            <li class="nav-section-label">Menu</li>
            <li><a href="customer_dashboard.php" class="cust-home <?= $current_page === 'customer_dashboard.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="place_order.php"         class="cust-order <?= $current_page === 'place_order.php' ? 'active' : '' ?>">Place an Order</a></li>
            <li class="nav-section-label">Account</li>
            <li><a href="logout.php" class="cust-logout">Logout</a></li>
        </ul>

        <?php else: ?>
        <!-- ADMIN NAVIGATION -->
        <ul>
            <li class="nav-section-label">Overview</li>
            <li><a href="index.php"     class="dashboard <?= $current_page === 'index.php'     ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="reports.php"   class="reports   <?= $current_page === 'reports.php'   ? 'active' : '' ?>">Reports</a></li>
            <li class="nav-section-label">Fleet</li>
            <li><a href="trucks.php"    class="trucks    <?= $current_page === 'trucks.php'    ? 'active' : '' ?>">Trucks</a></li>
            <li><a href="drivers.php"   class="drivers   <?= $current_page === 'drivers.php'   ? 'active' : '' ?>">Drivers</a></li>
            <li class="nav-section-label">Operations</li>
            <li><a href="trips.php"     class="trips     <?= $current_page === 'trips.php'     ? 'active' : '' ?>">Trips</a></li>
            <li><a href="customers.php" class="customers <?= $current_page === 'customers.php' ? 'active' : '' ?>">Customers</a></li>
            <li class="nav-section-label">Account</li>
            <li><a href="logout.php" class="logout">Logout</a></li>
        </ul>
        <?php endif; ?>
    </nav>

    <div class="container">
