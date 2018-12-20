#!/bin/bash
APP_PATH='/application'
EXTENSION_PATH='/application/extension'
DOWNLOAD_PATH='/home/download'
WWW_PATH='/var/www'
phpize='/application/php/bin/phpize'
php_config='/application/php/bin/php-config'
php_ini='/application/php/lib/php.ini'
php_version='php-7.2.11'
MYSQL_PASSWD='12345678'
REDIS_PASSWD='198721'
SCRIPT_PATH=''

function init()
{
    SCRIPT_PATH="`pwd`/"

	if [ ! -d ${DOWNLOAD_PATH} ];then
		mkdir ${DOWNLOAD_PATH}
	
	if [ ! -d ${APP_PATH} ];then
		mkdir ${APP_PATH} -p
	fi

	fi
        if [ ! -d ${EXTENSION_PATH} ];then
		mkdir ${EXTENSION_PATH}
	fi

	if [ ! -d ${WWW_PATH} ];then
		mkdir ${WWW_PATH} -p
	fi

	/bin/cp -rf ${SCRIPT_PATH}/download/* ${DOWNLOAD_PATH}/
}

function installBasic()
{
	yum install -y gcc glibc libxml2 libxml2-devel openssl openssl-devel bzip2 bzip2-devel libcurl libcurl-devel libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel gmp gmp-devel libmcrypt libmcrypt-devel readline readline-devel libxslt libxslt-devel libicu-devel vim wget autoconf git screen ntp
}

function installPHP7()
{
[ -f /application/php/bin/php ] && {
    tips 'PHP7'
	return
}

cd ${DOWNLOAD_PATH}
tar jxvf "${DOWNLOAD_PATH}/${php_version}.tar.bz2"

cd "${DOWNLOAD_PATH}/${php_version}"
./configure \
--prefix=/application/${php_version} \
--enable-fpm \
--with-fpm-user=nginx  \
--with-fpm-group=nginx \
--enable-inline-optimization \
--disable-debug \
--disable-rpath \
--enable-shared  \
--enable-soap \
--with-libxml-dir \
--with-xmlrpc \
--with-openssl \
--with-mhash \
--with-pcre-regex \
--with-zlib \
--enable-bcmath \
--with-iconv \
--with-bz2 \
--enable-calendar \
--with-curl \
--with-cdb \
--enable-dom \
--enable-exif \
--enable-fileinfo \
--enable-filter \
--with-pcre-dir \
--enable-ftp \
--with-gd \
--with-openssl-dir \
--with-jpeg-dir \
--with-png-dir \
--with-zlib-dir  \
--with-freetype-dir \
--enable-gd-jis-conv \
--with-gettext \
--with-gmp \
--with-mhash \
--enable-json \
--enable-mbstring \
--enable-mbregex \
--enable-mbregex-backtrack \
--with-libmbfl \
--with-onig \
--enable-pdo \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
--with-zlib-dir \
--with-pdo-sqlite \
--with-readline \
--enable-session \
--enable-shmop \
--enable-simplexml \
--enable-sockets  \
--enable-sysvmsg \
--enable-sysvsem \
--enable-sysvshm \
--enable-wddx \
--with-libxml-dir \
--with-xsl \
--enable-zip \
--enable-mysqlnd-compression-support \
--with-pear \
--enable-pcntl \
--enable-opcache

make && make install
}

function setup_link()
{
	[ ! -e /application/php ] && {
		ln -s /application/${php_version}    /application/php
	}
	[ ! -e /usr/local/bin/php ] && {
		ln -s /application/php/bin/php  /usr/local/bin/php
	}
	[ ! -e /usr/local/bin/php-fpm ] && {
		ln -s /application/php/sbin/php-fpm  /usr/local/bin/php-fpm
	}
}

function init_config()
{
	[ ! -e /application/php/lib/php.ini ] && {
		cp ${DOWNLOAD_PATH}/${php_version}/php.ini-production /application/php/lib/php.ini
	}
	[ ! -e /application/php/etc/php-fpm.conf ] && {
		cp /application/php/etc/php-fpm.conf.default /application/php/etc/php-fpm.conf
	}
	[ ! -e /application/php/etc/php-fpm.d/www.conf ] && {
		cp /application/php/etc/php-fpm.d/www.conf.default /application/php/etc/php-fpm.d/www.conf
	}

	# 设置时区
	set_php_timezone
}

function install_nginx()
{
    [ -e /application/nginx/sbin/nginx ] && {
        tips 'nginx'
        return
    }

	[ ! -e /application/nginx/sbin/nginx ] && {
		
		cd ${DOWNLOAD_PATH}
		
		id nginx > /dev/null

		[ $? -ne 0 ] && {
			useradd nginx  -s /sbin/nologin -M
		}	
		
		version='nginx-1.12.2'

		[ -f "${version}.tar.gz" ] && {
   			basepath=${SCRIPT_PATH}
			module="${basepath}extension/nginx-accesskey-master"
			[ ! -d "${EXTENSION_PATH}/nginx-accesskey-master" ] && {
                /bin/cp -r  ${module} "${EXTENSION_PATH}/"
			}

			tar xvzf "${version}.tar.gz"
			cd ${version} && ./configure --user=nginx --group=nginx --prefix=/application/${version}/ --with-http_stub_status_module --with-http_ssl_module -with-http_secure_link_module --add-module=${EXTENSION_PATH}/nginx-accesskey-master
			make && make install
			[ $? -eq 0 ] && {
			     ln -s /application/${version} /application/nginx
			     ln -s /application/nginx/sbin/nginx /usr/bin/nginx	
			}
		}
	}
      
}

function install_phpredis()
{
	cat /application/php/lib/php.ini | grep 'extension=redis.so' > /dev/null 2>&1
	if [ $? -eq 0 ]
	then
	    tips 'php-redis'
	    return
	else
	 # 检测到未进行安装 进行安装
       version='redis-4.1.1'
	   file_name="${version}.tgz"
	   cd "${DOWNLOAD_PATH}"
	   tar xvzf ${file_name}
	   cd ${version}
	   ${phpize}
	   ./configure --with-php-config=${php_config}
	   make && make install
	   [ $? -eq 0 ] && {
	   	cat>>${php_ini}<<EOF
extension=redis.so
EOF

}
    fi
}

function tips()
{
    mecho "√ 系统已安装${1}"
}

function install_phpSeaslog()
{
	cat /application/php/lib/php.ini | grep 'extension=seaslog.so' > /dev/null 2>&1
	if [ $? -eq 0 ]
	then
	   tips 'Seaslog'
	else
	   cd "${DOWNLOAD_PATH}"
	   tar xvzf 'SeasLog-1.8.6.tgz'
	   cd SeasLog-1.8.6	
	   ${phpize}
	   ./configure --with-php-config=${php_config}
	   make && make install
	   cat>>${php_ini}<<EOF
[SeasLog]
;configuration for php SeasLog module
extension = seaslog.so
;默认log根目录
seaslog.default_basepath = "/var/www/log"
;默认logger目录
seaslog.default_logger = "default"
;日期格式配置 默认"Y-m-d H:i:s"
seaslog.default_datetime_format = "Y-m-d H:i:s"
;日志格式模板 默认"%T | %L | %P | %Q | %t | %M"
seaslog.default_template = "%L | %M"
;是否以type分文件 1是 0否(默认)
seaslog.disting_type = 1
;是否每小时划分一个文件 1是 0否(默认)
seaslog.disting_by_hour = 0
;是否启用buffer 1是 0否(默认)
seaslog.use_buffer = 0
;buffer中缓冲数量 默认0(不使用buffer_size)
seaslog.buffer_size = 100
;记录日志级别，数字越大，根据级别记的日志越多。
;0-EMERGENCY 1-ALERT 2-CRITICAL 3-ERROR 4-WARNING 5-NOTICE 6-INFO 7-DEBUG 8-ALL
;默认8(所有日志)
;   注意, 该配置项自1.7.0版本开始有变动。
;   在1.7.0版本之前, 该值数字越小，根据级别记的日志越多:
;   0-all 1-debug 2-info 3-notice 4-warning 5-error 6-critical 7-alert 8-emergency
;   1.7.0 之前的版本，该值默认为0(所有日志);
seaslog.level = 8
;自动记录错误 默认1(开启)
seaslog.trace_error = 1
;自动记录异常信息 默认0(关闭)
seaslog.trace_exception = 0
;日志存储介质 1File 2TCP 3UDP (默认为1)
seaslog.appender = 1
;接收ip 默认127.0.0.1 (当使用TCP或UDP时必填)
seaslog.remote_host = "127.0.0.1"
;接收端口 默认514 (当使用TCP或UDP时必填)
seaslog.remote_port = 514
;过滤日志中的回车和换行符 (默认为0)
seaslog.trim_wrap = 0
;是否开启抛出SeasLog自身异常  1开启(默认) 0否
seaslog.throw_exception = 1
;是否开启忽略SeasLog自身warning  1开启(默认) 0否
seaslog.ignore_warning = 1
EOF

fi

}

function install_mysql()
{
	rpm -qa | grep mariadb-server
	[ $? -eq 0 ] && {
	    tips 'mysql'
	    return
	}

	[ $? -ne 0 ] && {
		yum -y install mariadb mariadb-server
		systemctl start mariadb
		systemctl enable mariadb
		echo "now let's begin mysql_secure_installation "
		if [ ! -e /usr/bin/expect ] 
			then  yum install expect -y 
		fi
		echo '#!/usr/bin/expect
		set timeout 10
		set password [lindex $argv 0]
		spawn mysql_secure_installation
		expect {
			"Enter current password for root" { send "\r"; exp_continue}
			"Set root password" { send "Y\r" ; exp_continue}
			"New password" { send "$password\r"; exp_continue}
			"Re-enter new password" { send "$password\r"; exp_continue}
			"Remove anonymous users" { send "Y\r"; exp_continue}
			"Disallow root login remotely" { send "n\r"; exp_continue}
			"Remove test database and access to it" { send "Y\r"; exp_continue}
			"Reload privilege tables now" { send "Y\r"; exp_continue}
			"Cleaning up" { send "\r"}
		}
		interact ' > mysql_secure_installation.exp 
		chmod +x mysql_secure_installation.exp
		./mysql_secure_installation.exp ${MYSQL_PASSWD}
 		rm -rf ./mysql_secure_installation.exp 
	}
}

function install_redis()
{
   [ -f '/usr/local/bin/redis-server' ] && {
      tips 'Redis'
      return
   }

   [ ! -f '/usr/local/bin/redis-server' ] && {
	cd ${DOWNLOAD_PATH}
	tar -zvxf "redis-5.0.0.tar.gz"
	cd redis-5.0.0
	make && make install > /dev/null 2>&1
	cd utils/
	echo | /bin/bash install_server.sh
	mkdir /etc/systems/system -p
	touch /etc/systems/system/redis_6379.service
        cat>/etc/systems/system/redis_6379.service<<EOF
systemd.service
[Unit]
Description = Redis on port 6379
[Service]
Type=forking
ExeStart=/etc/init.d/redis_6379 start
ExeStop =/etc/init.d/redis_6379 stop
[Install]
WantedBy=multi-user.target
EOF

cat /etc/redis/6379.conf | grep '# requirepass foobared' > /dev/null
[ $? -eq 0 ] && {
   sed -i "s/# requirepass foobared/requirepass ${REDIS_PASSWD}/" /etc/redis/6379.conf
   redis-cli shutdown
   redis-server /etc/redis/6379.conf
}
   }
}

function install_web()
{
   [ ! -e ${SCRIPT_PATH}superweb.tar.gz ] && {
   	   error "web压缩包不存在";
   	   exit 1
   }	

   basepath=${SCRIPT_PATH}
   cd ${basepath}	
   if [ -d "${WWW_PATH}/superweb" ]
   then
        tips 'web'
   else
        tar xvzf ./superweb.tar.gz -C /var/www/
   fi

   mysql -uroot -p12345678 -e "show databases" | grep superweb > /dev/null
   [ $? -ne 0 ] && {
   mysql -uroot -p12345678 <<EOF 2>/dev/null
create database superweb;
use superweb;
source /var/www/superweb/console/install/install.sql;
EOF
}
  #赋予权限
  chmod 777 -R ${WWW_PATH}/superweb/storage
  mv /application/nginx/conf/nginx.conf /application/nginx/conf/nginx.conf.backup
  cp -r ${WWW_PATH}/superweb/vagrant/nginx/nginx.dev.conf /application/nginx/conf/nginx.conf
  echo "*/1 * * * * /var/www/superweb/crontab.sh > /dev/null 2>&1" >>  /var/spool/cron/root
  systemctl restart crond
}

function install_api()
{
   [ ! -e ${SCRIPT_PATH}superAPI.tar.gz ] && {
   	   error "API压缩包不存在"
   	   exit 1
   }	

   basepath=${SCRIPT_PATH}
   cd ${basepath}	
   if [ -d "${WWW_PATH}/superAPI" ]
   then
        tips 'API'
   else
        tar xvzf ./superAPI.tar.gz -C /var/www/
    fi
}

function setting_firewalld()
{
  firewall-cmd --list-port | grep 8088 > /dev/null
  [ $? -ne 0 ] && {
  	firewall-cmd --add-port=8088/tcp --permanent > /dev/null
  }
 
  firewall-cmd --list-port | grep 12389 > /dev/null
  [ $? -ne 0 ] && {
  	firewall-cmd --add-port=12389/tcp --permanent > /dev/null
  }
  
  systemctl restart firewalld > /dev/null
}


function service_api()
{
    cd "${WWW_PATH}/superAPI/public/";

    if [ ! $1 ]
    then
       ps -ef | grep 'WorkerMan: master process' | grep -v grep > /dev/null
       if [ $? -ne 0 ]
       then
          php start.php restart -d > /dev/null  2>&1
          run_tips "API服务"
       else
          service_api "restart"
       fi
    else
        if [ $1 == 'status' ]
        then
          php start.php status
        elif [ $1 == 'reload' ]
        then
          php start.php reload
        elif [ $1 == 'restart' ]
        then
           php start.php restart -d > /dev/null  2>&1
           running_tips "API服务"
        fi
    fi
}

function service_log()
{
   create_screen
   screen_name=$"my_service"
   ps -ef | grep 'log/analyse' | grep -v grep > /dev/null
   if [ $? -ne 0 ]
   then
        cmd="cd ${WWW_PATH}/superweb;php yii log/analyse &"
	    screen -r ${screen_name} -p 0 -X stuff "${cmd}"
	    screen -r ${screen_name} -p 0 -X stuff $'\n'
	    run_tips "日志服务"
   else
        running_tips "日志服务"
   fi
}

function run_tips()
{
    mecho "√ ${1}已启动" "green"
}

function running_tips()
{
    mecho "√ ${1}已经在运行" "green"
}

function service_queue()
{
   create_screen
   ps -ef | grep 'queue/listen' | grep -v grep > /dev/null
   if [ $? -ne 0 ]
   then
        cmd="cd ${WWW_PATH}/superweb;php yii queue/listen &"
	    screen -r ${screen_name} -p 0 -X stuff "${cmd}"
	    screen -r ${screen_name} -p 0 -X stuff $'\n'
	    run_tips "队列服务"
   else
        running_tips "队列服务"
   fi
}

function service_php_fpm()
{
   ps -ef | grep php-fpm | grep -v grep > /dev/null
   if [ $? -ne 0 ]
   then
        php-fpm > /dev/null 2>&1
        run_tips "php-fpm"
   else
        running_tips "php-fpm"
   fi
}

function service_nginx()
{
    ps -ef | grep 'master process nginx' | grep -v grep > /dev/null
   if [ $? -ne 0 ]; then
	    nginx > /dev/null
	    run_tips "Nginx"
   else
        nginx -s reload
        running_tips "Nginx"
   fi
}

function create_screen()
{
   # 开启日志服务 队列服务
   screen_name=$"my_service"
   screen -list | grep ${screen_name} | grep -v grep > /dev/null
   [ $? -ne 0 ] && {
   	screen -dmS ${screen_name}
   }
}

function boot()
{
   service_nginx
   service_php_fpm
   service_api
   service_log
   service_queue
}

function finish()
{
   ip=`/sbin/ifconfig -a|grep inet|grep -v 127.0.0.1|grep -v inet6|awk '{print $2}'|tr -d "addr:"`
   echo -e "\e[1;32m ----------------------------------------------";
   echo -e "\e[1;32m 安装成功 : \e"
   echo -e "\e[1;32m 后台管理 : http://${ip}:8088/admin.php"
   echo -e "\e[1;32m 安装位置 : ${WWW_PATH}/superweb"
   echo -e "\e[1;32m 用户名   : admin"
   echo -e "\e[1;32m 用户密码 : 12345678"
   echo -e "\e[1;32m 数据库   : superweb"
   echo -e "\e[1;32m 密码     : 12345678"

   echo -e "\e[1;32m API服务  : http://$ip:12389/ \e"
   echo -e "\e[1;32m 安装位置 : ${WWW_PATH}/superAPI \e"
   
   echo -e "\e[1;32m php位置  : ${APP_PATH}/php \e"
   echo -e "\e[1;32m nginx位置: ${APP_PATH}/nginx \e"
   echo -e "\e[1;32m redis位置: ${APP_PATH}/redis \e"
  
   echo -e "\e[1;32m ----------------------------------------------\e[0m";
}

function mecho()
{
   case $2 in
    red)
        code='31m'
        ;;
    green)
        code='32m'
        ;;
    yellow)
        code='33m'
        ;;
    blue)
        code='34m'
        ;;
    purple)
        code='35m'
        ;;
    green-blue)
        code='36m'
        ;;
    white)
        code='37m'
        ;;
    *)
        code='36m'
        ;;
    esac

    case $3 in
    black)
        background='40;'
        ;;
    red)
        background='41;'
        ;;
    green)
        background='42;'
        ;;
    yellow)
        background='43;'
        ;;
    blue)
        background='44;'
        ;;
    purple)
        background='45;'
        ;;
    skyblue)
        background='46;'
        ;;
    white)
        background='47;'
        ;;
    *)
        background=''
        ;;
    esac

    echo -e "\033[${background}${code} ${1}  \033[0m"
}

function error()
{
   echo -e "\033[31m ${1}  \033[0m"
}

function goodbye()
{
    mecho ":) 感谢使用,有缘再会~" "blue" "white"
}

function register_to_global()
{
    path="$(pwd)/${0}"
    [ -e ${path} ] && {
        if [ -d  /usr/local/sbin/ ];then
            /bin/cp ${path} /usr/local/sbin/iptv.sh
        else
            /bin/cp ${path} /usr/sbin/iptv.sh
        fi

    }
}

function manage_menu()
{
    mecho "★★★★★★★★★★IPTV系统管理助手★★★★★★★★★★" "blue" "white"
    mecho "       ➤ (1) 服务管理"
    mecho "       ➤ (2) 独立安装"
    mecho "       ➤ (3) 退出菜单" "white"

    read -p "请输入选择: " input
    case ${input} in
    1) service_menu;;
    2) stand_alone_install;;
    3) goodbye;;
    esac
}

function close_apache()
{
    systemctl disable httpd;
    systemctl disable httpd;
}

function set_php_timezone()
{
    sed -i 's/;date.timezone =/date.timezone = Asia/Shanghai;/' ${php_ini}
}

function set_linux_timezone()
{
   timedatectl set-timezone Asia/Shanghai
   ntpdate ntp1.aliyun.com
}

function api_manage()
{
    mecho "★★★★★★★★★★ API服务管理 ★★★★★★★★★★" "blue" "white"
    mecho "      ➤ (1) 服务状态"  "green-blue"
    mecho "      ➤ (2) 热重载" "green-blue"
    mecho "      ➤ (3) 服务重启" "green-blue"
    mecho "      ➤ (4) 退出" "white"

    read -p "请输入选择: " input
    case ${input} in
    1) service_api 'status';;
    2) service_api 'reload';;
    3) service_api 'restart';;
    4) goodbye;;
    esac
}

function install_helper()
{
    register_to_global
    mecho "★★★★★★★★★★★★★★★ 操作结果 ★★★★★★★★★★★★★★" "blue" "white"
    mecho "安装成功                               " "blue" "white"
    mecho "使用方式: iptv.sh                      " "blue" "white"
    mecho "★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★" "blue" "white"
}

function install_menu()
{
    mecho "★★★★★★★★★★IPTV系统软件安装★★★★★★★★★★" "blue" "white"
    mecho "系统要求: CentOS                    " "blue" "white"
    mecho "版本要求: > 7.1                     " "blue" "white"
    mecho "★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★★" "blue" "white"

    mecho "       ➤ (1) 一键安装"
    mecho "       ➤ (2) 独立安装"
    mecho "       ➤ (3) 安装管理助手"
    mecho "       ➤ (4) 退出菜单" "white"

    read -p "请输入选择: " input
    case ${input} in
    1) one_key_install;;
    2) stand_alone_install;;
    3) install_helper;;
    4) goodbye;;
    esac
}

function menu()
{
    position=$(cd "$(dirname "$0")";pwd)

    if [ ${position} == "/usr/local/sbin" ]
    then
        manage_menu
    else
        install_menu
    fi
}

function service_menu()
{
   mecho "★★★★★★★★★★★ IPTV服务管理 ★★★★★★★★★★★" "blue" "white"

   mecho "      ➤ (1) API服务" 'green-blue'
   mecho "      ➤ (2) 日志服务" "green-blue"
   mecho "      ➤ (3) 队列服务" "green-blue"
   mecho "      ➤ (4) 定时任务" "green-blue"
   mecho "      ➤ (5) 返回上级" "white"
   mecho "      ➤ (6) 退出" "white"

    read -p "请输入选择: " input
    case ${input} in
    1) api_manage;;
    2) service_log;;
    3) service_queue;;
    4) service_crontab;;
    5) menu;;
    5) goodbye;;
    esac
}

function stand_alone_install()
{

mecho "★★★★★★★★★★ 独立安装软件 ★★★★★★★★★★" "blue" "white"
mecho "     ➤ (1) 安装 基础软件"
mecho "     ➤ (2) 安装 PHP7"
mecho "     ➤ (3) 安装 Nginx"
mecho "     ➤ (4) 安装 Seaslog"
mecho "     ➤ (5) 安装 Redis"
mecho "     ➤ (6) 安装 php-redis"
mecho "     ➤ (7) 安装 Mysql"
mecho "     ➤ (8) 安装 Web后台"
mecho "     ➤ (9) 安装 API服务"
mecho "     ➤ (10) 设置 防火墙"
mecho "     ➤ (11) 设置 中国时区"
mecho "     ➤ (12) 返回上级" "white"
mecho "     ➤ (13) 退出" "white"

read -p "请输入选择: " input
case ${input} in
1) installBasic;;
2) installPHP7;;
3) install_nginx;;
4) install_phpSeaslog;;
5) install_redis;;
6) install_phpredis;;
7) install_mysql;;
8) install_web;;
9) install_api;;
10) setting_firewalld;;
11) set_linux_timezone;;
12) menu;;
13) goodbye;;

esac

}

function one_key_install()
{
	init
	installBasic
	install_nginx
	installPHP7
	install_phpSeaslog
	setup_link
	init_config
	install_redis
	install_phpredis
	install_mysql
    install_web
    install_api
    close_apache
    boot
	setting_firewalld
	register_to_global
	set_linux_timezone
	finish
}

function main()
{
  menu
}


main
