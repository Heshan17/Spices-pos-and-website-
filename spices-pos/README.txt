SPICES WEB  (PHP + MySQL, runs on XAMPP)

1. Open the XAMPP Control Panel and start Apache and MySQL.
2. Copy this whole "spice-pos" folder into  C:\xampp\htdocs\
3. Open  http://localhost/spice-pos/
   The first request creates the database "spice_pos", all tables, 30 sample products
   and the demo users automatically.
   (Optional manual way: open http://localhost/phpmyadmin, Import -> database.sql)
4. Other devices on the same Wi-Fi: http://YOUR-PC-IP/spice-pos/

Demo logins: admin / admin123   cashier / cash123   -> change them under "Security".
If you set a MySQL root password, put it in config.php.
Backup: phpMyAdmin -> spice_pos -> Export.
