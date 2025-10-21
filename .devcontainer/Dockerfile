# constuction à partir de l'image de base https://hub.docker.com/r/microsoft/devcontainers-php et la version PHP 8.3
FROM mcr.microsoft.com/devcontainers/php:8.3

RUN \
    # Met à jour la liste des paquets
    apt-get update \
    # Installe MariaDB et wget (node, npm, php, composer sont déjà présents dans l'image de base)
    && apt-get install -y mariadb-server wget \
    # Nettoie le cache des paquets pour alléger l'image
    && rm -rf /var/lib/apt/lists/* \
    # Installe les extensions PHP nécessaires pour MariaDB/MySQL
    && docker-php-ext-install mysqli pdo pdo_mysql \
    # Télécharge la dernière version de phpMyAdmin
    && wget https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.tar.gz \
    # Décompresse l’archive téléchargée
    && tar xvf phpMyAdmin-latest-all-languages.tar.gz \
    # Déplace le dossier extrait vers /usr/src/phpmyadmin
    && mv phpMyAdmin-*-all-languages /usr/src/phpmyadmin \
    # Supprime l’archive pour économiser de l’espace
    && rm phpMyAdmin-latest-all-languages.tar.gz \
    # Copie le fichier de configuration exemple de phpMyAdmin
    && cp /usr/src/phpmyadmin/config.sample.inc.php /usr/src/phpmyadmin/config.inc.php \
    # Configure phpMyAdmin pour une authentification par configuration
    && echo "\$cfg['Servers'][\$i]['auth_type'] = 'config';" >> /usr/src/phpmyadmin/config.inc.php \
    # Définit l’utilisateur par défaut pour phpMyAdmin
    && echo "\$cfg['Servers'][\$i]['user'] = 'admin';" >> /usr/src/phpmyadmin/config.inc.php \
    # Définit le mot de passe de l’utilisateur
    && echo "\$cfg['Servers'][\$i]['password'] = 'admin_password';" >> /usr/src/phpmyadmin/config.inc.php \
    # Définit l’hôte de connexion à la base de données
    && echo "\$cfg['Servers'][\$i]['host'] = '127.0.0.1';" >> /usr/src/phpmyadmin/config.inc.php \
    # Autorise la connexion sans mot de passe (optionnel)
    && echo "\$cfg['Servers'][\$i]['AllowNoPassword'] = true;" >> /usr/src/phpmyadmin/config.inc.php \
    # Crée le dossier temporaire nécessaire à phpMyAdmin
    && mkdir -p /usr/src/phpmyadmin/tmp \
    # Donne les droits à www-data sur ce dossier
    && chown -R www-data:www-data /usr/src/phpmyadmin/tmp \
    # Donne tous les droits sur ce dossier (lecture/écriture/exécution)
    && chmod 777 /usr/src/phpmyadmin/tmp \
    # Crée un super utilisateur MariaDB
    && service mariadb start \
    && mysql -e "CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin_password';" \
    && mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' WITH GRANT OPTION;"  \
    && mysql -e "FLUSH PRIVILEGES;"

EXPOSE 3306 8080

CMD ["mysqld"]
