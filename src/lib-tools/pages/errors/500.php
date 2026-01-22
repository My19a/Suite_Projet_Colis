<?php
$errorMessage = $errorMessage ?? 'Une erreur inattendue s\'est produite.';
$errorTrace = $errorTrace ?? null;
$showDebug = (getenv('APP_ENV') === 'development' || (isset($config) && $config['env'] === 'development'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Erreur serveur</title>
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
            --red-border: #fecaca;
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
            max-width: 600px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 32px;
            border: 2px solid var(--red-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--red-bg);
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

        .debug-info {
            text-align: left;
            background: var(--bg-card);
            border: 1px solid var(--red-border);
            border-radius: 12px;
            margin-bottom: 32px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .debug-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 16px;
            background: var(--red-bg);
            border-bottom: 1px solid var(--red-border);
            font-size: 13px;
            font-weight: 600;
            color: var(--red);
        }

        .debug-header svg {
            width: 16px;
            height: 16px;
        }

        .debug-content {
            padding: 16px;
        }

        .debug-message {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 13px;
            color: var(--text);
            line-height: 1.6;
            word-break: break-word;
        }

        .debug-trace {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .debug-trace summary {
            cursor: pointer;
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .debug-trace pre {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 11px;
            color: var(--text-secondary);
            line-height: 1.5;
            overflow-x: auto;
            white-space: pre-wrap;
            max-height: 200px;
            overflow-y: auto;
            background: var(--bg);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
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
            margin-top: 48px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .footer code {
            font-family: 'SF Mono', 'Fira Code', monospace;
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>

        <div class="error-code">500</div>

        <h1>Erreur serveur</h1>

        <p class="description">
            Quelque chose s'est mal passé de notre côté. Nous avons été notifiés et travaillons à résoudre le problème.
        </p>

        <?php if ($showDebug && ($errorMessage || $errorTrace)): ?>
        <div class="debug-info">
            <div class="debug-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0 1 12 12.75Z" />
                </svg>
                Debug Info (dev only)
            </div>
            <div class="debug-content">
                <div class="debug-message"><?= htmlspecialchars($errorMessage) ?></div>
                <?php if ($errorTrace): ?>
                <div class="debug-trace">
                    <details>
                        <summary>Stack trace</summary>
                        <pre><?= htmlspecialchars($errorTrace) ?></pre>
                    </details>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="actions">
            <button onclick="location.reload()" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Réessayer
            </button>
            <a href="/" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Accueil
            </a>
        </div>

        <div class="footer">
            Si le problème persiste, contactez le support avec le code <code><?= date('Ymd-His') ?></code>
        </div>
    </div>
</body>
</html>
