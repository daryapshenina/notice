#!/bin/bash

start() {
	service memcached start
	service php5-fpm start
	chmod 0666 /var/run/php5-fpm.sock
	nginx
	rabbitmq-server
	service td-agent start
	php bin/chat-server.php
	mongod --fork --dbpath /var/lib/mongodb/ --smallfiles --logpath /var/log/mongodb.log --logappend
	start-stop-daemon --start --pidfile /var/run/sshd.pid --exec /usr/sbin/sshd -- -p 22
	echo "READY"

	## fixed by sotnikovds <sotnikovds@altarix.ru>

	# export NLS_LANG=AMERICAN_CIS.AL32UTF8
	indexer --all
	searchd

	service postgresql start

	sudo -u postgres psql -c "CREATE USER notice WITH SUPERUSER PASSWORD 'iddqd225';"
	sudo -u postgres psql -c "CREATE DATABASE notice;"

	export PGPASSWORD=iddqd225
	

	echo "Import from docker-init.sql ..."
	psql -U gibdd -h localhost -d gibdd -w -a -f /var/www/gibdd2/protected/migrations/docker-init.sql > /dev/null
	echo "Done."


	# psql -U gibdd -h localhost -d gibdd -w -a -f /var/www/PgMigration/s*.sql
	# find . -type f -name "script_*.sql" -execdir psql -U gibdd -h localhost -d gibdd -w -a -f {} +
	# find . -type f -name 'script_*.sql'  -printf "%T+\t%p\n" | sort | awk '{print $2}'

	# arr=($(ls -v /var/www/PgMigration/*.sql))

	# for i in "${arr[@]}"; do
	# 	echo "Import from $i ..."
	# 	psql -U gibdd -h localhost -d gibdd -w -a -f $i > /dev/null
	# 	echo "Done."
	# done

	fsmonitor -q -d /var/www "+//default.conf" bash -c "nginx -s reload &&  echo 'nginx reload with default.conf'"
}

case "${1}" in
	bash) 
		bash
		;;
	*)             
		start
		;;
esac