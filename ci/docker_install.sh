#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

apt-get update -yqq
apt-get install git sudo -yqq

curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit

mkdir /data/apps -p

cd /data/apps/
git clone https://gitlab.com/eneiluj/gpxedit-oc gpxedit

sudo -u www-data php occ upgrade
sudo -u www-data php occ maintenance:mode --off

ls /data
ls /data/apps
