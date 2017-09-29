#!/usr/bin/env bash

# *****************************
# 
# Скрипт генерации конфигов миграций.
# Создает файлы migrations-db.php, migrations.yml и необходимые директории.
# Интерактивно запрашивает параметры конфига migrations-db.php.
# 
# @author SotnikovDS
# @email sotnikovds@altarix.ru
# *****************************

BASEDIR=$(dirname "$0")
DOCTRINE_DIR="${BASEDIR}/../vendor/bin"
MODULES=()

for file in ${BASEDIR}/../module/*; do
	MODULES+=("${file##*/}")
done

function printMain {

	if [[ $1 -gt 0 ]]; then
		clear
	fi

	echo "\
**********************************************
* Создание необходимых файлов для реализации *
*          миграций в Zend-модуле            *
**********************************************

Какой модуль Zend использовать ?
"
	COUNTER=1

	for module in ${MODULES[@]}; do
	  echo "${COUNTER}) ${module}"
	  COUNTER=$(($COUNTER+1))
	done
	echo "0 -- Exit"
	echo ""
}

function getUserChoice {
	re='^[0-9]+$'
	until [[ $MODULE_NUMBER =~ $re ]]; do
		read MODULE_NUMBER

		if ! [[ $MODULE_NUMBER =~ $re ]] ; then
		   printMain 1
		   echo "Введено не число!"
		else
			if [[ $MODULE_NUMBER -eq 0 ]]; then
				exit
			fi
			if [[ $MODULE_NUMBER -gt ${#MODULES[@]} ]]; then
				printMain 1
				echo "Введен слишком большой номер!"
				MODULE_NUMBER=""
			fi
		fi
	done
	
	MODULE_NUMBER=$(($MODULE_NUMBER-1))
}

function echoErrorMessage {
	local TXT_COLOR='\033[36m'
	local COLOR='\033[3;33m'
	local NC='\033[0m' # No Color	
	echo "${TXT_COLOR}Возможно, Вам нужно запустить${NC} ${COLOR}doctrine-migrations-deploy${NC}"
}

function getModuleName {
	MODULE_NAME=${MODULES[$MODULE_NUMBER]}
}

printMain
getUserChoice
getModuleName

MIGRATIONS_DIR="${BASEDIR}/../module/${MODULE_NAME}/migrations"
CONFIGS_DIR="${MIGRATIONS_DIR}/configs"
VERSIONS_DIR="${MIGRATIONS_DIR}/versions"

EXISTS=0

TXT_COLOR='\033[36m'
COLOR='\033[3;33m'
NC='\033[0m' # No Color	

if [[ -d $MIGRATIONS_DIR ]]; then
	echo -e "\
Миграции уже существуют!
${TXT_COLOR}Вы можете выполнить${NC} \
${COLOR}doctrine-migrations-remove${NC}\
${TXT_COLOR}, 
чтобы удалить текущую конфигурацию миграций${NC}
"
	EXISTS=1
fi

if [[ $EXISTS -eq 1 ]]; then
	exit 1
fi

PARAM_NAMES=('dbname' 'driver' 'host' 'user' 'password')

MIGRATION_DB_FILE="<?php\nreturn array(\n"

for paramName in ${PARAM_NAMES[@]}; do
	echo "Введите параметр '${paramName}':"
	read paramValue
	MIGRATION_DB_FILE=${MIGRATION_DB_FILE}"\t'${paramName}' => '${paramValue}',\n"
	# echo $paramValue
done

MIGRATION_DB_FILE=${MIGRATION_DB_FILE}");"

mkdir -p $CONFIGS_DIR
mkdir -p $VERSIONS_DIR

echo -e $MIGRATION_DB_FILE > "${CONFIGS_DIR}/migrations-db.php"

echo "\
name: Doctrine Migrations
migrations_namespace: Migrations # namespace of the migration classes
#table_name: public.user # table name to use for version records
migrations_directory: ../versions # directory to create migration files/classes in\
" > "${CONFIGS_DIR}/migrations.yml"

echo "Готово!"
