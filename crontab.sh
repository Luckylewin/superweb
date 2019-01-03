#!/bin/bash
cd /var/www/superweb

php -v | grep "PHP 7" > /dev/null
[ $? -eq 0 ] && {
   php yii crontab/index
}
