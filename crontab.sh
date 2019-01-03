#!/bin/bash
cd /var/www/superweb

php -v | grep "PHP 7"
[ $? -eq 0 ] && {
   php yii crontab/index
}
