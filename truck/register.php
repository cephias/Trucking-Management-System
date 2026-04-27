<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kai Trucking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #AFEEEE 0%, #000080 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .register-wrapper {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .register-image {
            flex: 1;
            min-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .register-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('trucks1.jpg') center/cover;
            opacity: 0.3;
            z-index: 0;
        }

        .image {
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            z-index: 2;
            backdrop-filter: blur(10px);
        }

        .image h3 {
            color: #AFEEEE;
            font-size: 22px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .image p {
            color: #6b7280;
            font-size: 14px;
        }

        .image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-top: 16px;
        }

        .register-form {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #AFEEEE, #000080);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .register-header p {
            color: #6b7280;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.8);
        }

        .form-group input:focus {
            outline: none;
            border-color: #AFEEEE;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .error {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px 20px;
            border-radius: 12px;
            border-left: 4px solid #ef4444;
            margin-bottom: 24px;
            font-size: 14px;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%       { transform: translateX(-5px); }
            75%       { transform: translateX(5px); }
        }

        button {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #AFEEEE 0%, #000080 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .login-link p { color: #6b7280; font-size: 14px; }

        .login-link a {
            color: #AFEEEE;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover { text-decoration: underline; }

        @media (max-width: 768px) {
            .register-wrapper {
                flex-direction: column;
                max-width: 400px;
            }
            .register-image {
                min-width: auto;
                padding: 40px 20px;
                height: 200px;
            }
            .register-form {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">

        <div class="register-image">
            <div class="image">
                <h3>Kai Trucking</h3>
                <p>Management System</p>
                <img src="trucks1.jpg" alt="Trucking">
            </div>
        </div>

        <div class="register-form">
            <div class="register-header">
                <h1>Create Account</h1>
                <p>Register to get started</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required
                           placeholder="Choose a username" autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Choose a password" autocomplete="new-password">
                </div>

                <button type="submit">Create Account</button>
            </form>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Sign in here</a></p>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('username').focus();
    </script>
</body>
</html>