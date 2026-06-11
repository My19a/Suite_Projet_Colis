#!/bin/sh
# Attend que la base de donnees accepte les connexions avant de lancer Apache,
# pour eviter une erreur au premier chargement de page.
set -e

DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"

echo "[entrypoint] Attente de la base de donnees ${DB_HOST}:${DB_PORT}..."
i=0
until php -r 'exit(@fsockopen(getenv("DB_HOST") ?: "db", (int)(getenv("DB_PORT") ?: 3306)) ? 0 : 1);' 2>/dev/null; do
    i=$((i + 1))
    if [ "$i" -ge 30 ]; then
        echo "[entrypoint] La base n'est toujours pas joignable, on demarre quand meme."
        break
    fi
    sleep 2
done

echo ""
echo "=================================================="
echo "  Application prete  ->  http://localhost:8000"
echo "  phpMyAdmin         ->  http://localhost:8080"
echo "=================================================="
echo ""
exec apache2-foreground
