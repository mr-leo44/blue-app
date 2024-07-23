
log:
	tail -f /opt/lampp/logs/php_error_log
log-apache: 
	tail -f /var/log/apache2/access.log /var/log/apache2/error.log
