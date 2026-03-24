#!/bin/bash

# Import database to Railway MySQL
# Make sure you have exported your database as mpcms_backup.sql first

echo "Importing database to Railway..."
mysql -h nozomi.proxy.rlwy.net -u root --port 35252 --protocol=TCP railway < mpcms_backup.sql

if [ $? -eq 0 ]; then
    echo "Database imported successfully!"
else
    echo "Import failed. Please check your SQL file and connection."
fi
