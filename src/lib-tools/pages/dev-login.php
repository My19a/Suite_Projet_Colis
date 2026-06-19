<?php

require_once __DIR__ . '/../Auth/User.php';
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Helpers/helpers.php';
require_once __DIR__ . '/../../public/models/Model.php';
require_once __DIR__ . '/../../public/models/UserRepository.php';

if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
    $role = $_SESSION['user']->getRole();
    $redirects = [
        'admin' => '/admin/dashboard',
        'responsable_colis' => '/postal/dashboard',
        'demandeur' => '/departement/dashboard',
        'editeur_bc' => '/finance/dashboard',
    ];
    header('Location: ' . ($redirects[$role] ?? '/postal/dashboard'));
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'] ?? '';
    $role = $_POST['role'] ?? 'demandeur';

    if (!empty($uid)) {
        $user = UserRepository::findByUidCas($uid);

        if (!$user) {
            $user = UserRepository::create($uid, [
                'displayName' => 'Dev User - ' . $uid,
                'mail' => $uid . '@dev.local',
            ], $role);
        }

        $_SESSION['user'] = $user;
        $_SESSION['dev_uid_cas'] = $uid;
        $_SESSION['authenticated'] = true;

        $redirects = [
            'admin' => '/admin/dashboard',
            'responsable_colis' => '/postal/dashboard',
            'demandeur' => '/departement/dashboard',
            'editeur_bc' => '/finance/dashboard',
        ];

        header('Location: ' . ($redirects[$role] ?? '/'));
        exit;
    } else {
        $error = 'Veuillez entrer un identifiant.';
    }
}

$existingUsers = [];
try {
    $model = Model::getModel();
    $stmt = $model->bd->query("
        SELECT u.uid_cas, u.fullName, r.libelle as role
        FROM utilisateur u
        INNER JOIN role r ON u.role_id = r.id_role
        ORDER BY r.libelle, u.fullName
    ");
    $existingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Silently fail if DB not available
}

// Rôles proposés : [valeur, libellé, icône du site]
$rolesDispo = [
    ['admin',             'Administrateur BD', 'utilisateurs'],
    ['responsable_colis', 'Responsable colis', 'reception'],
    ['demandeur',         'Demandeur',         'departements'],
    ['editeur_bc',        'Éditeur de BC',     'budget'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion (dev) – Suivi Colis</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
    <style>
        .page-auth { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .auth-box { width: 100%; max-width: 420px; }
        .auth-marque { text-align: center; margin-bottom: 18px; }
        .auth-logo { margin: 0 auto 8px; display: flex; justify-content: center; }
        .auth-logo img { width: 72px; height: 72px; object-fit: contain; }
        .auth-titre { font-size: 21px; font-weight: 650; color: var(--princ); letter-spacing: -0.3px; }
        .auth-sous { font-size: 12.5px; color: var(--texte-doux); margin-top: 2px; }
        .auth-carte { background: var(--surface); border: 1px solid var(--bord); border-radius: var(--r); padding: 22px; }

        .auth-roles { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
        .auth-role input { position: absolute; opacity: 0; pointer-events: none; }
        .auth-role label {
            display: flex; flex-direction: column; align-items: center; gap: 7px;
            padding: 12px 6px; background: var(--surface); border: 1px solid var(--bord);
            border-radius: var(--r); cursor: pointer; text-align: center;
            transition: border-color .1s ease, background-color .1s ease;
        }
        .auth-role label:hover { border-color: var(--accent); }
        .auth-role input:checked + label { border-color: var(--princ); background: var(--princ-doux); }
        .auth-role input:focus-visible + label { outline: 2px solid var(--princ); outline-offset: 2px; }
        .auth-role-icone {
            width: 30px; height: 30px; border-radius: var(--r);
            background: var(--accent-doux); color: var(--princ);
            display: flex; align-items: center; justify-content: center;
        }
        .auth-role input:checked + label .auth-role-icone { background: var(--princ); color: #fff; }
        .auth-role-nom { font-size: 11.5px; font-weight: 500; color: var(--texte-doux); }
        .auth-role input:checked + label .auth-role-nom { color: var(--princ); font-weight: 600; }

        .auth-sep { display: flex; align-items: center; gap: 12px; margin: 20px 0 12px; color: var(--texte-leger); font-size: 12px; }
        .auth-sep::before, .auth-sep::after { content: ""; flex: 1; height: 1px; background: var(--bord-doux); }
        .auth-users { max-height: 220px; overflow-y: auto; border: 1px solid var(--bord); border-radius: var(--r); }
        .auth-user-form { display: block; }
        .auth-user {
            display: flex; align-items: center; gap: 11px; width: 100%;
            padding: 10px 12px; background: transparent; border: none;
            border-bottom: 1px solid var(--bord-doux); cursor: pointer; text-align: left;
            transition: background-color .1s ease;
        }
        .auth-user-form:last-child .auth-user { border-bottom: none; }
        .auth-user:hover { background: var(--princ-doux); }
        .auth-user-avatar {
            width: 32px; height: 32px; border-radius: var(--rond);
            background: var(--accent-doux); color: var(--princ);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; flex-shrink: 0;
        }
        .auth-user-info { flex: 1; min-width: 0; }
        .auth-user-nom { font-size: 13px; font-weight: 500; color: var(--texte); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .auth-user-role { font-size: 11.5px; color: var(--texte-leger); }
        .auth-pied { text-align: center; margin-top: 16px; font-size: 11.5px; color: var(--texte-leger); }
    </style>
</head>
<body class="page-auth">
    <main class="auth-box">
        <div class="auth-marque">
            <div class="auth-logo"><img src="/assets/img/logo-colis.png" alt="Suivi Colis"></div>
            <h1 class="auth-titre">Suivi Colis</h1>
            <p class="auth-sous">Connexion de développement</p>
        </div>

        <div class="auth-carte">
            <div class="message message-attn" style="margin-bottom: 18px;">
                <span class="message-corps">Environnement de développement — authentification simulée.</span>
            </div>

            <?php if ($error): ?>
                <div class="message message-err" style="margin-bottom: 18px;">
                    <span class="message-corps"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="champ">
                    <label class="etiquette" for="uid">Identifiant CAS (fictif)</label>
                    <input type="text" id="uid" name="uid" class="saisie" placeholder="ex : jdupont" required autocomplete="off" autofocus>
                </div>

                <div class="champ">
                    <label class="etiquette">Rôle</label>
                    <div class="auth-roles">
                        <?php foreach ($rolesDispo as [$valeur, $libelle, $icn]): ?>
                            <div class="auth-role">
                                <input type="radio" name="role" value="<?= $valeur ?>" id="role-<?= $valeur ?>" <?= $valeur === 'demandeur' ? 'checked' : '' ?>>
                                <label for="role-<?= $valeur ?>">
                                    <span class="auth-role-icone"><?= icone($icn, 17) ?></span>
                                    <span class="auth-role-nom"><?= htmlspecialchars($libelle) ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="bouton bouton-principal" style="width: 100%; margin-top: 4px;">Se connecter</button>
            </form>

            <?php if (!empty($existingUsers)): ?>
                <div class="auth-sep">Utilisateurs existants</div>
                <div class="auth-users">
                    <?php foreach ($existingUsers as $u): ?>
                        <form method="POST" class="auth-user-form">
                            <input type="hidden" name="uid" value="<?= htmlspecialchars($u['uid_cas']) ?>">
                            <input type="hidden" name="role" value="<?= htmlspecialchars(strtolower(str_replace(' ', '_', $u['role']))) ?>">
                            <button type="submit" class="auth-user">
                                <span class="auth-user-avatar"><?= strtoupper(substr($u['fullName'] ?? $u['uid_cas'], 0, 2)) ?></span>
                                <span class="auth-user-info">
                                    <span class="auth-user-nom"><?= htmlspecialchars($u['fullName'] ?? $u['uid_cas']) ?></span>
                                    <span class="auth-user-role"><?= htmlspecialchars(libelleRole($u['role'])) ?></span>
                                </span>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <p class="auth-pied">© <?= date('Y') ?> IUT de Villetaneuse — Suivi Colis</p>
    </main>
</body>
</html>
