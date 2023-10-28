# oct/27/2023 22:16:32 by RouterOS 6.49.7
# software id = 50EY-FQVL
#
# model = RB952Ui-5ac2nD
# serial number = CC400BCCEC3F
/interface bridge
add name=bridge1
/interface wireless
set [ find default-name=wlan1 ] country=argentina disabled=no ssid=MikroTik \
    station-roaming=enabled
set [ find default-name=wlan2 ] country=argentina disabled=no ssid=MikroTik \
    station-roaming=enabled
/interface wireless security-profiles
set [ find default=yes ] supplicant-identity=MikroTik
/user group
set full policy="local,telnet,ssh,ftp,reboot,read,write,policy,test,winbox,pas\
    sword,web,sniff,sensitive,api,romon,dude,tikapp"
/interface bridge port
add bridge=bridge1 interface=ether1
add bridge=bridge1 interface=ether2
add bridge=bridge1 interface=ether3
add bridge=bridge1 interface=ether4
add bridge=bridge1 interface=ether5
/ip neighbor discovery-settings
set discover-interface-list=!dynamic
/ip dhcp-client
add disabled=no interface=bridge1
/system clock
set time-zone-name=America/Argentina/Buenos_Aires
