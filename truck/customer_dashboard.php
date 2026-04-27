<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<style>
    .dashboard-page {
        min-height: 100vh;
        background: url('tunnel.jpg') center/cover no-repeat fixed;
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
    .customer-wrap {
        font-family: 'DM Sans', sans-serif;
    }

    .hero {
        position: relative;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        border-radius: 24px;
        padding: 80px 60px;
        margin-bottom: 60px;
        overflow: hidden;
        text-align: center;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 20% 50%, rgba(102,126,234,0.25) 0%, transparent 70%),
            radial-gradient(ellipse 50% 60% at 80% 30%, rgba(118,75,162,0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    .hero-truck-icon {
        font-size: 64px;
        display: block;
        margin: 0 auto 24px;
        filter: drop-shadow(0 0 30px rgba(102,126,234,0.6));
        animation: floatTruck 4s ease-in-out infinite;
    }

    @keyframes floatTruck {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }

    .hero h1 {
        font-family: 'Syne', sans-serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.1;
        margin: 0 0 16px;
        position: relative;
        z-index: 1;
    }

    .hero h1 span {
        background: linear-gradient(90deg, #3a4cec, #482e9e, #2736c1);
        background-size: 200%;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }

    .hero p {
        color: rgba(255,255,255,0.65);
        font-size: 18px;
        font-weight: 300;
        max-width: 520px;
        margin: 0 auto 36px;
        line-height: 1.7;
        position: relative;
        z-index: 1;
    }

    .hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #AFEEEE, #000080);
        color: #fff;
        text-decoration: none;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: 16px;
        padding: 18px 36px;
        border-radius: 50px;
        position: relative;
        z-index: 1;
        transition: transform 0.25s, box-shadow 0.25s;
        box-shadow: 0 8px 30px rgba(102,126,234,0.4);
        letter-spacing: 0.3px;
    }

    .hero-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(102,126,234,0.55);
        text-decoration: none;
    }

    /* Stats bar */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 60px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        transition: transform 0.25s, box-shadow 0.25s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }

    .stat-icon {
        font-size: 36px;
        display: block;
        margin: 0 auto 12px;
    }

    .stat-number {
        font-family: 'Syne', sans-serif;
        font-size: 2.4rem;
        font-weight: 800;
        background: linear-gradient(135deg, #AFEEEE, #000080);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
        margin-bottom: 6px;
    }

    .stat-label {
        color: #94a3b8;
        font-size: 13px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Services */
    .section-title {
        font-family: 'Syne', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: White;
        margin: 0 0 8px;
    }

    .section-sub {
        color: #94a3b8;
        font-size: 15px;
        margin: 0 0 36px;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 60px;
    }

    .service-card {
        background: white;
        border-radius: 20px;
        padding: 36px 28px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 24px rgba(0,0,0,0.05);
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        position: relative;
        overflow: hidden;
    }

    .service-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #AFEEEE, #000080);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(102,126,234,0.12);
        border-color: #e0e7ff;
    }

    .service-card:hover::after {
        transform: scaleX(1);
    }

    .service-icon {
        font-size: 42px;
        display: block;
        margin-bottom: 18px;
    }

    .service-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 10px;
    }

    .service-desc {
        color: #64748b;
        font-size: 14px;
        line-height: 1.65;
        margin: 0;
    }

    /* Why Us */
    .why-section {
        background: linear-gradient(135deg, #0f172a, #1e1b4b);
        border-radius: 24px;
        padding: 60px;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
    }

    .why-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(102,126,234,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .why-section .section-title { color: #fff; }
    .why-section .section-sub { color: rgba(255,255,255,0.5); }

    .why-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .why-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 24px;
        backdrop-filter: blur(10px);
        transition: background 0.25s;
    }

    .why-item:hover {
        background: rgba(255,255,255,0.08);
    }

    .why-check {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #AFEEEE, #000080);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(102,126,234,0.4);
    }

    .why-text h4 {
        font-family: 'Syne', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 4px;
    }

    .why-text p {
        color: rgba(255,255,255,0.5);
        font-size: 13px;
        margin: 0;
        line-height: 1.5;
    }

    /* CTA bottom */
    .bottom-cta {
        text-align: center;
        padding: 60px 40px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        border: 1px solid #f1f5f9;
        margin-bottom: 40px;
    }

    .bottom-cta h2 {
        font-family: 'Syne', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 12px;
    }

    .bottom-cta p {
        color: #94a3b8;
        font-size: 15px;
        margin: 0 0 32px;
    }

    @media (max-width: 900px) {
        .stats-bar, .services-grid { grid-template-columns: 1fr 1fr; }
        .why-grid { grid-template-columns: 1fr; }
        .hero { padding: 50px 30px; }
        .why-section { padding: 40px 28px; }
    }

    @media (max-width: 600px) {
        .stats-bar, .services-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="dashboard-page">
<div class="customer-wrap" style="max-width: 1100px; margin: 0 auto; padding: 40px 24px;">

    <!-- Hero -->
    <div class="hero">
        <span class="hero-truck-icon">🚚</span>
        <h1>Kenya's Most Reliable<br><span>Freight Partner</span></h1>
        <p>From Nairobi to the coast and beyond ,we move your cargo safely, on time, every time.</p>
        <a href="place_order.php" class="hero-cta">
            Place an Order Now ➡️
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-bar">
        <?php
        $trips_done = $pdo->query("SELECT COUNT(*) FROM trips WHERE status='completed'")->fetchColumn();
        $truck_count = $pdo->query("SELECT COUNT(*) FROM trucks WHERE status != 'maintenance'")->fetchColumn();
        $customer_count = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
        ?>
        <div class="stat-card">
            <span class="stat-icon">✅</span>
            <div class="stat-number"><?= number_format($trips_done) ?>+</div>
            <div class="stat-label">Trips Completed</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">🚛</span>
            <div class="stat-number"><?= $truck_count ?>+</div>
            <div class="stat-label">Trucks in Fleet</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">🤝</span>
            <div class="stat-number"><?= $customer_count ?>+</div>
            <div class="stat-label">Happy Customers</div>
        </div>
    </div>

    <!-- Services -->
    <h2 class="section-title">Our Services</h2>
    <p class="section-sub">Freight solutions tailored to your needs</p>

    <div class="services-grid">
        <div class="service-card">
            <span class="service-icon">🏙️</span>
            <div class="service-title">Long-Haul Transport</div>
            <p class="service-desc">Nairobi to Mombasa, Kisumu, Nakuru and all major corridors. Professional drivers, GPS-tracked trucks.</p>
        </div>
        <div class="service-card">
            <span class="service-icon">📦</span>
            <div class="service-title">Cargo Handling</div>
            <p class="service-desc">Fragile, bulk, refrigerated, we handle all cargo types with care and proper securing equipment.</p>
        </div>
        <div class="service-card">
            <span class="service-icon">⏱️</span>
            <div class="service-title">Express Delivery</div>
            <p class="service-desc">Time-sensitive shipments handled with priority scheduling and dedicated driver assignment.</p>
        </div>
        <div class="service-card">
            <span class="service-icon">🏭</span>
            <div class="service-title">Warehouse Pickup</div>
            <p class="service-desc">We collect directly from your warehouse or factory gate with no middlemen or delays.</p>
        </div>
        <div class="service-card">
            <span class="service-icon">📊</span>
            <div class="service-title">Shipment Tracking</div>
            <p class="service-desc">Real-time visibility on all your active orders. Know where your cargo is at every step.</p>
        </div>
        <div class="service-card">
            <span class="service-icon">🤝</span>
            <div class="service-title">Corporate Contracts</div>
            <p class="service-desc">Monthly retainer rates for businesses with regular freight needs. Priority fleet access guaranteed.</p>
        </div>
    </div>

    <!-- Why Us -->
    <div class="why-section">
        <h2 class="section-title">Why Choose Kai Trucking?</h2>
        <p class="section-sub">Built on trust, driven by reliability</p>
        <div class="why-grid">
            <div class="why-item">
                <div class="why-check">🛡️</div>
                <div class="why-text">
                    <h4>Fully Insured Cargo</h4>
                    <p>Every shipment is covered. Your goods are protected from pickup to delivery.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="why-check">📍</div>
                <div class="why-text">
                    <h4>GPS Tracked Fleet</h4>
                    <p>All trucks are monitored in real time so we always know where your cargo is.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="why-check">👨‍✈️</div>
                <div class="why-text">
                    <h4>Licensed & Vetted Drivers</h4>
                    <p>Every driver is fully licensed, trained and background checked.</p>
                </div>
            </div>
            <div class="why-item">
                <div class="why-check">⚡</div>
                <div class="why-text">
                    <h4>Same Day Booking</h4>
                    <p>Place your order and get a confirmed truck within hours, not days.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom CTA -->
    <div class="bottom-cta">
        <h2>Ready to Ship with Us?</h2>
        <p>Fill in a quick form and we'll get your cargo moving right away.</p>
        <a href="place_order.php" class="hero-cta" style="display: inline-flex;">
            Place an Order ➡️
        </a>
    </div>

</div>

<?php include 'footer.php'; ?>
</div>