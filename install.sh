#!/bin/bash

echo -e "\nrun: cd app"
cd app;

echo -e "\nrun: composer install"
composer install;

echo -e "\nrun: php cli/schema/init.php"
php cli/schema/init.php
