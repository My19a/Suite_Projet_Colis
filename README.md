# SAE Gestion des Colis

Application de gestion des colis pour l'IUT Pour Monsieur.
<p align="center">
  <img src="./src/public/assets/img/monsieur-butelle.jpg" width="200" height="200" style="border-radius: 50%;">
</p>


![Schema relationnel](./Documentation/Schema-relation.png)

## Prerequis

- PHP 8.3 minimum
- Composer
- Docker (optionnel, pour la base de donnees)

## Installation

### 1. Installer PHP et les extensions

**Linux (Ubuntu/Debian)**
```
make install-linux
```

**MacOS**
```
make install-macos
```

**Windows**

Telecharger PHP 8.3 : https://www.php.net/downloads.php

Video for install PHP CLI Windows -> https://www.youtube.com/watch?v=n04w2SzGr_U

Ou utiliser WSL avec Ubuntu si Windows ne marche pas.

### 2. Configurer la base de donnees

Avec Docker :
```
cd Database && docker compose -f docker-bd.yaml up -d
```

### 3. Configurer l'environnement

Copier le fichier d'exemple .env.example et adapter les secrets à vous :
```
make env
```

Modifier `src/.env` selon votre configuration (mot de passe BDD, etc).

### 4. Installer les dependances Composer

```
make i
```

### 5. Lancer le serveur

```
make r
```

Le serveur demarre sur http://localhost:8000

## Commandes utiles

| Commande | Description |
|----------|-------------|
| `make install-linux` | Installe PHP et extensions (Ubuntu) |
| `make install-macos` | Installe PHP et extensions (MacOS) |
| `make env` | Copie le fichier .env.example |
| `make i` | Installe les dependances Composer |
| `make r` | Lance le serveur PHP |

## Acces base de donnees Docker

Entrer dans le conteneur :
```
docker exec -it sae_db sh
```

Se connecter a MariaDB :
```
mysql -u root -proot_password
```
