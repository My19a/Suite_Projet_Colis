<?php
$homeUrl = '/';
if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
    $userRole = $_SESSION['user']->getRole();
    $redirects = [
        'admin' => '/admin/dashboard',
        'postal_iut' => '/postal/dashboard',
        'postal_univ' => '/postal-univ/dashboard',
        'finance' => '/finance/dashboard',
        'directeur' => '/directeur/dashboard',
        'departement' => '/departement/dashboard',
    ];
    $homeUrl = $redirects[$userRole] ?? '/';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Accès refusé</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #f8fafc;
            --bg-card: #ffffff;
            --text: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --blue: #2563eb;
            --blue-light: #3b82f6;
            --blue-dark: #1d4ed8;
            --blue-bg: #eff6ff;
            --red: #dc2626;
            --red-bg: #fef2f2;
        }

        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(ellipse at top left, rgba(37, 99, 235, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(37, 99, 235, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .container {
            text-align: center;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 32px;
            border: 2px solid var(--border);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-card);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .icon svg {
            width: 40px;
            height: 40px;
            stroke: var(--red);
        }

        .error-code {
            font-size: 100px;
            font-weight: 800;
            letter-spacing: -4px;
            line-height: 1;
            background: linear-gradient(135deg, var(--red) 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 24px;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .description {
            color: var(--text-secondary);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--blue);
            color: white;
        }

        .btn-primary:hover {
            background: var(--blue-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-secondary {
            background: var(--bg-card);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--blue-bg);
            border-color: var(--blue);
        }

        .btn svg {
            width: 18px;
            height: 18px;
        }

        .footer {
            margin-top: 64px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>

        <div class="error-code">403</div>

        <h1>Accès refusé</h1>

        <p class="description">
            Vous n'avez pas les permissions nécessaires pour accéder à cette ressource.
            Vérifiez vos droits ou contactez un administrateur.
        </p>

        <div class="actions">
            <a href="<?= htmlspecialchars($homeUrl) ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Tableau de bord
            </a>
            <button onclick="history.back()" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Retour
            </button>
        </div>

        <div class="footer">
            SAE Suivi Colis - Sorbonne Université
        </div>
    </div>
</body>
</html>
