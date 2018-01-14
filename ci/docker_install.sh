#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

apt-get update -yqq
apt-get install git -yqq

curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit

mkdir /data/apps -p

sudo -u nextcloud occ upgrade
sudo -u nextcloud occ maintenance:mode --off

cd /data/apps/
git clone https://gitlab.com/eneiluj/gpxedit-oc gpxedit

ls /data
ls /data/apps
