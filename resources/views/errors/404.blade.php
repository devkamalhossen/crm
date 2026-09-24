<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Intellecit Ltd CRM</title>
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --accent: #06B6D4;
            --bg-dark: #0F172A;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Glow Animation */
        .bg-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.25) 0%, rgba(6, 182, 212, 0.15) 50%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            animation: pulse 8s ease-in-out infinite alternate;
        }

        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.6; }
            100% { transform: translate(-50%, -50%) scale(1.2); opacity: 1; }
        }

        /* Card Container */
        .error-container {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 580px;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Branding Header */
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: 0.5px;
            margin-bottom: 2rem;
            text-transform: uppercase;
        }

        .brand-logo i {
            color: var(--accent);
        }

        .brand-logo span {
            color: var(--primary);
        }

        /* Visual Illustration / Badge */
        .error-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            background: rgba(79, 70, 229, 0.1);
            border: 1px solid rgba(79, 70, 229, 0.3);
            border-radius: 50%;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .error-badge i {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Text Content */
        .error-code {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.5rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #94A3B8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-main);
        }

        .error-description {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Action Buttons */
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: #FFFFFF;
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.39);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(79, 70, 229, 0.55);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Footer Info */
        .footer-note {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .footer-note a {
            color: var(--accent);
            text-decoration: none;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 2rem 1.5rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>

    <div class="error-container">
        <!-- CRM Branding -->
        <div class="brand-logo">
            <i class="fa-solid fa-chart-line"></i> Intellec<span>IT</span> CRM
        </div>

        <!-- Animated Icon -->
        <div class="error-badge">
            <i class="fa-solid fa-database"></i>
        </div>

        <!-- Error Details -->
        <div class="error-code">404</div>
        <h1 class="error-title">Data Record Not Found</h1>
        <p class="error-description">
            আপনি যে পেজ বা CRM রেকর্ডটি খুঁজছেন তা হয়তো মুছে ফেলা হয়েছে, লিঙ্কটি পরিবর্তন করা হয়েছে অথবা আপনার এক্সেস পারমিশন নেই।
        </p>

        <!-- Navigation Buttons -->
        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fa-solid fa-house"></i> Go to Dashboard
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Go Back
            </a>
        </div>

        <!-- Footer Help -->
        <div class="footer-note">
            Need assistance? <a href="https://devkamalhossen.online/">Contact CRM Support</a> or report an issue.
        </div>
    </div>

</body>
</html>