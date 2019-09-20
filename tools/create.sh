dbname=noname

mysql -e "CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci"
mysql $dbname < table.sql
mysql $dbname < data.sql
