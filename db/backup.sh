#!/usr/bin/sh
mariadb-dump symfony_camping_dc -uroot -padmin > /root/init.sql
echo "Sauvegarde terminée"