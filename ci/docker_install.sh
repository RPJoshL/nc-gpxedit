#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

apt-get update -yqq
apt-get install git sudo -yqq

curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit

#mkdir /data/apps -p
#cd /data/apps/
cd /var/www/html/custom_apps/
git clone https://gitlab.com/eneiluj/gpxedit-oc gpxedit

sudo -u www-data php /var/www/html/occ maintenance:install --database "sqlite" --admin-user "admin" --admin-pass "password"
sudo -u www-data php /var/www/html/occ app:enable gpxedit
sudo -u www-data php /var/www/html/occ upgrade
sudo -u www-data php /var/www/html/occ maintenance:mode --off
sudo -u www-data php /var/www/html/occ app:check-code gpxedit

ls /var/www/html/custom_apps/
