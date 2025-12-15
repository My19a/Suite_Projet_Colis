<?php

namespace SAE\Repositories;

use SAE\Auth\User;

require_once __DIR__ . '/../Models/Model.php';

class UserRepository
{
    private static ?\PDO $db = null;

    private static function getDb(): \PDO
    {
        if (self::$db === null) {
            $model = \Model::getModel();
            self::$db = $model->bd;
        }
        return self::$db;
    }

    /**
     * Trouve un utilisateur par son UID CAS
     *
     * @param string $uid UID CAS de l'utilisateur
     * @return User|null
     */
    public static function findByUidCas(string $uid): ?User
    {
        $db = self::getDb();
        $stmt = $db->prepare("
            SELECT u.id_utilisateur, u.uid_cas, u.fullName, u.email, r.libelle as role, u.departement_id
            FROM utilisateur u
            INNER JOIN role r ON u.role_id = r.id_role
            WHERE u.uid_cas = :uid
        ");

        $stmt->execute(['uid' => $uid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User(
            (int) $data['id_utilisateur'],
            $data['uid_cas'],
            $data['fullName'],
            $data['email'],
            strtolower($data['role']), // Normaliser en minuscules
            $data['departement_id'] ? (int) $data['departement_id'] : null
        );
    }

    /**
     * Crée un nouvel utilisateur à partir des données CAS
     *
     * @param string $uid UID CAS
     * @param array $casAttributes Attributs retournés par le CAS
     * @param string $role Rôle à assigner ('admin', 'postal_iut', 'acteur')
     * @return User
     */
    public static function create(string $uid, array $casAttributes, string $role): User
    {
        $db = self::getDb();

        // Récupérer l'ID du rôle
        $roleMap = [
            'admin' => 1,
            'postal_iut' => 2,
            'acteur' => 3,
        ];

        $roleId = $roleMap[$role] ?? 3; // Par défaut: acteur

        $fullName = $casAttributes['displayName'] ?? $casAttributes['cn'] ?? $uid;
        $email = $casAttributes['mail'] ?? "{$uid}@univ-paris13.fr";
        $accessToken = $casAttributes['access_token'] ?? 'dev_token_' . time();

        $stmt = $db->prepare("
            INSERT INTO utilisateur (uid_cas, access_token_api_cas, fullName, email, role_id)
            VALUES (:uid, :access_token, :nom, :email, :role_id)
        ");

        $stmt->execute([
            'uid' => $uid,
            'access_token' => $accessToken,
            'nom' => $fullName,
            'email' => $email,
            'role_id' => $roleId,
        ]);

        $userId = (int) $db->lastInsertId();

        return new User(
            $userId,
            $uid,
            $fullName,
            $email,
            $role,
            null,
            $casAttributes
        );
    }

    /**
     * Met à jour les informations d'un utilisateur
     *
     * @param int $userId
     * @param array $data Données à mettre à jour
     * @return bool
     */
    public static function update(int $userId, array $data): bool
    {
        $db = self::getDb();
        $fields = [];
        $params = ['id' => $userId];

        if (isset($data['fullName'])) {
            $fields[] = 'fullName = :nom';
            $params['nom'] = $data['fullName'];
        }

        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }

        if (isset($data['departement_id'])) {
            $fields[] = 'departement_id = :dep';
            $params['dep'] = $data['departement_id'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE utilisateur SET " . implode(', ', $fields) . " WHERE id_utilisateur = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute($params);
    }
}
