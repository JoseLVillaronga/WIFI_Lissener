# WIFI_Lissener
Use a smool Mikrotik dual band to lissen AP close and give basic information, this is for fast test of Home CPEs like ONTs or Cable modems

# Server side:
This can run on a smool virtualized server with basic linux, only install MySQL and PHP, I use same PHP to create a web server, install Daemonize with "apt install daemonize" if you install Debian,
The scripts from "root" folder place it to "/root" and fill crontab with this lines and replace "password" for MySQL password,

*/30 * * * *    /usr/sbin/ntpdate pool.ntp.org

*/1 * * * *  /usr/bin/bash /root/wifi2.sh

*/1 * * * *  /usr/bin/bash /root/wifi5.sh

*/1 * * * *  /usr/sbin/daemonize /usr/bin/php -S 0.0.0.0:80 -t /var/www/html/

* */1 * * *  /usr/sbin/ntpdate pool.ntp.org

17 12 * * *  /usr/bin/bash /root/mata.sh

30 19 * * *  /usr/bin/bash /root/mata.sh

*/1 * * * *  mysql -u jlvillaronga -ppassword -D teccam -e "DELETE FROM teccam.wifi_2 WHERE w2_fecha < (NOW() - INTERVAL 1 MINUTE)"

*/1 * * * *  mysql -u jlvillaronga -ppassword -D teccam -e "DELETE FROM teccam.wifi_5 WHERE w2_fecha < (NOW() - INTERVAL 1 MINUTE)"

10 12 * * *  mysql -u jlvillaronga -ppassword -D teccam -e "TRUNCATE TABLE teccam.wifi_2"

10 12 * * *  mysql -u jlvillaronga -ppassword -D teccam -e "TRUNCATE TABLE teccam.wifi_5"

* 2 * * *  /usr/sbin/reboot
