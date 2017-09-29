#!/usr/bin/env bash

# *****************************
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
*********************************************
*     Запуск миграций для БД Zend-модуля    *
*********************************************

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

${DOCTRINE_DIR}/doctrine-migrations migrations:migrate \
--db-configuration ${BASEDIR}/../module/${MODULE_NAME}/migrations/configs/migrations-db.php \
--configuration ${BASEDIR}/../module/${MODULE_NAME}/migrations/configs/migrations.yml \
|| echo -e `echoErrorMessage`