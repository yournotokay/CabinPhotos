#!/bin/sh
MONITORDIR="/var/www/html/cabinImages/"
inotifywait -m -r -e create --format '%w%f' "${MONITORDIR}" | while read NEWFILE
do
	echo "Watch fired ${NEWFILE}";
	php /var/www/html/inotify/inotify.php ${NEWFILE}
done
