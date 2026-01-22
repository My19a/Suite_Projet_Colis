![Modélisation](./Documentation/Schema-relation.png)

# 1) You need install PHP 8.3

(Windows installation PHP 8.3) -> https://www.php.net/downloads.php?usage=web&os=windows&osvariant=linux-ubuntu&version=default)

Video for install PHP CLI Windows -> https://www.youtube.com/watch?v=n04w2SzGr_U

- To lance the project backend (PS : You need PHP CLI 8.3 minimum)
- Need Composer (Packages PHP manager)
- CAS utilise XM parser les réponses XML du serveur CAS
- Pilote Mysql for PHP

For windows is very diffcult, or use WSL ubuntu for linux if Windows crash :/

Use this command for Linux (distribution Ubuntu)
```
make install-linux
```

Use this command for MacOS
```
make install-macos
```

# 2) Setup Database with Docker or another

If you want use Docker to build and run image for speed (mariadb and phpMyadmin)
```
cd Database && docker compose -f docker-bd.yaml up -d
```

# 3) Setup .env secrets, cp .env.example and adapte for your environnement (password ect) in src/.env

```
make env
```

# 4) Install dependances composer (lib generator PDF, Auth CAS, ECT)

- For install dependances composer
```
make i
```

# 5) For run server, let's gooo

- For run server PHP
```
make r
```

## Problèmes ? Commandes utilitaires

(if the composer.json not found make this in Folder /src)
```
composer init --name="sae/backend" --require="apereo/phpcas:^1.6" --no-interaction
```

Entrer dans le conteneur maria db:

```
docker exec -it sae_db sh
```

```
mysql -u root -proot_password
```
