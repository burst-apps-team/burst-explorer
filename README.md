<img align="right" width="120" height="120" title="Burst Logo" src="https://raw.githubusercontent.com/burst-apps-team/Marketing_Resources/master/BURST_LOGO/PNG/icon_blue.png" />

# Burst-Explorer
[![GPLv3](https://img.shields.io/badge/license-GPLv3-blue.svg)](LICENSE.txt)
[![Get Support at https://discord.gg/ms6eagX](https://img.shields.io/badge/join-discord-blue.svg)](https://discord.gg/ms6eagX)

Blockchain Explorer using PHP, MySQL, JavaScript and Bootstrap. It contains multiple helpful charts, a network observer, world map, and more

Requirements:
* BRS 2.3.0
* PHP 7.2
* PHP Extension: Memcached, bcmath, curl, json, mbstring, mcrypt, mail and mysqli
* PHP short tags enabled
* Cronjob for cron_.php every 60 second (Monitor cron execution times in logs/cron_)
* Cronjob for cron_network_status.php every 12 hours (Monitor cron execution times in logs/cron_network_status)
* Cronjob for cron_peers.php every hour (Monitor cron execution times in logs/cron_peers)
* Atleast 16GB ram avaliable

Installation:
* Make sure the BRS wallet is the newest release and in sync
* Import init_sql.sql
* Edit and fill in database.php, cron_.php, cron_network_status.php, cron_peers.php
* Setup cronjob as stated in the requirements
* After install it can take hours before the explorer is in sync
* Wait aprox 3 hours and the Explorer should be ready
* After the wait remove the comment out in cron_.php in line 10 (max_execution_time)
* If you do not wish to enforce SSL / HTTPS comment out index.php line 9

Features:
* Block Explorer
* Network observer
* World map
* E-mail notifications
* Management menu for flushing memcached, cron jobs and test enviroment
* Execution time loggin for sql and cronjob 