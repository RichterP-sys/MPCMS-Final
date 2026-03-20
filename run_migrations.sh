#!/bin/bash
cd c:\xampp\htdocs\MPCMS1\MPCMS
php artisan migrate --force
php artisan optimize:clear
