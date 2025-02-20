#!/usr/bin/sh

mariadb symfony_camping_dc -uroot -padmin < /root/init.sql
echo "Restauration terminée"
