#!/usr/bin/env bash

# *****************************
# @author SotnikovDS
# @email sotnikovds@altarix.ru
# *****************************

if [[ "$#" -lt 1 ]]; then
	echo -e "\
****************************************************
* Введите \
\033[3;31mtestsuite\
 \033[3;32mname\033[0m\
 из файла phpunit.xml.dist *
****************************************************"
	exit 1
fi

BASEDIR=$(dirname "$0")
PHPUNIT_DIR="${BASEDIR}/../vendor/bin"

${PHPUNIT_DIR}/phpunit --testsuite $1 || \
echo -e "\033[3;33mЗапустите скрипт из корня проекта\033[0m"
