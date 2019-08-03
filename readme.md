#### Requirements
1. Mysql 5.7.24
2. PHP 7.2.11 (modules: calendar,Core,ctype,curl,date,dom,exif,fileinfo,filter,ftp,gd,gettext,hash,iconv,intl,json,libxml,mbstring,mysqli,mysqlnd,openssl,pcntl,pcre,PDO,pdo_mysql,Phar,posix,readline,Reflection,session,shmop,SimpleXML,sockets,sodium,SPL,standard,sysvmsg,sysvsem,sysvshm,tokenizer,wddx,xml,xmlreader,xmlwriter,xsl,Zend OPcache,zip,zlib) Reccomended Zend OPcache
3. Composer
4. Git

#### Install
1. git clone
2. cp .env.example .env
3. Fill env params
4. composer install
4. php artisan key:generate
5. php artisan migrate


* Api prefix /api
* Api Documentation /api/documentation

