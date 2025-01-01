# WIFI_Lissener

WiFi monitoring system that uses a dual-band Mikrotik router to scan and collect information from nearby access points. Specifically designed for quick testing of home CPE equipment such as ONTs or Cable modems.

## Features

- Simultaneous monitoring on 2.4GHz and 5GHz bands
- Real-time data collection including:
  - AP MAC address
  - SSID
  - Frequency band
  - Channel width
  - Specific frequency
  - Signal strength
  - Noise level
  - Signal-to-Noise Ratio (SNR)
- REST API for JSON data queries
- Web interface for data visualization
- Automatic cleanup of old data
- Automatic time synchronization

## Requirements

### Server
- Linux (Debian/Ubuntu recommended)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Daemonize (`apt install daemonize`)

### Client
- Dual-band Mikrotik router
- Specific configuration provided in the Mikrotik-config folder

## Installation

### 1. Server Setup

1. Clone the repository:
   ```bash
   git clone [repository_URL]
   cd WIFI_Lissener
   ```

2. Install system dependencies:
   ```bash
   apt update
   apt install php mysql-server daemonize composer
   ```

3. Configure the database:
   ```bash
   mysql -u root -p < Server/wifi_mysql_config.sql
   ```

4. Copy server files:
   ```bash
   cp -r Server/* /var/www/html/
   cp Server/root/* /root/
   cd Server
   composer update
   cd ..
   ```

5. Configure database credentials:
   - Copy `.env.example` to `.env`
   - Edit `.env` with your MySQL credentials

6. Configure cron:
   ```bash
   crontab -e
   ```
   Add the following lines (adjust MySQL password):
   ```
   */30 * * * *    /usr/sbin/ntpdate pool.ntp.org
   */1 * * * *     /usr/bin/bash /root/wifi2.sh
   */1 * * * *     /usr/bin/bash /root/wifi5.sh
   */1 * * * *     /usr/sbin/daemonize /usr/bin/php -S 0.0.0.0:80 -t /var/www/html/
   */1 * * * *     mysql -u dbuser -ppassword -D teccam -e "DELETE FROM teccam.wifi_2 WHERE w2_fecha < (NOW() - INTERVAL 1 MINUTE)"
   */1 * * * *     mysql -u dbuser -ppassword -D teccam -e "DELETE FROM teccam.wifi_5 WHERE w2_fecha < (NOW() - INTERVAL 1 MINUTE)"
   10 12 * * *     mysql -u dbuser -ppassword -D teccam -e "TRUNCATE TABLE teccam.wifi_2"
   10 12 * * *     mysql -u dbuser -ppassword -D teccam -e "TRUNCATE TABLE teccam.wifi_5"
   * 2 * * *       /usr/sbin/reboot
   ```

### 2. Mikrotik Router Setup

1. Apply the configuration provided in the `Mikrotik-config` folder
2. Verify that the router has connectivity with the server

## Usage

1. Access the web interface:
   ```
   http://[server_IP]
   ```

2. Query data via API:
   ```
   http://[server_IP]/wifi-mt2.php  # 2.4GHz data
   http://[server_IP]/wifi-mt5.php  # 5GHz data
   ```

Data is updated every minute and is kept for 1 minute before being automatically deleted.

## Maintenance

The system includes the following automatic tasks:
- Cleanup of old records every minute
- Daily table truncation at 12:10
- System reboot at 2:00
- Time synchronization every 30 minutes

## Database Structure

The system uses two main tables:
- `wifi_2`: Stores 2.4GHz band data
- `wifi_5`: Stores 5GHz band data

Each table includes fields for:
- Unique ID
- MAC Address
- SSID
- Band
- Channel Width
- Frequency
- Signal
- Noise
- SNR
- Timestamp
