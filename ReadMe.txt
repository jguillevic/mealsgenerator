Prérequis :
- Php 7.0
- Apache ??? + modules ?
- allow_url_fopen à 1
- MySql ???

sudo apt-get install php7.2-mysql
sudo apt-get install php7.2-json
sudo apt-get install php7.2-mbstring
mod_ssl
mod_socache_shmcb
mod_vhost_alias

Editer /etc/php/7.2/apache2/php.ini en conséquence.

Apache :
- Affecter 0 à session_auto_start dans les fichiers de config.
- Installer un certificat ssl.
- En gros, activer la connexion sécurisé.