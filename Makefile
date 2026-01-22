r:
	cd src/public && php -S localhost:8000 router.php
i:
	cd src/ && composer install
install-linux:
	sudo apt install php8.3-cli && php -v && sudo apt install composer && sudo apt install php8.3-xml && sudo apt install php8.3-mysql
install-macos:
	brew update && brew install php@8.3 composer mysql && brew link --overwrite --force php@8.3 && php -v && composer --version && php -m | grep -E 'xml|pdo_mysql|mysqli' || true
env:
	cd src/ && cp .env.example .env
