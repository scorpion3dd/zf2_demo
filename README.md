![Logo](http://framework.zend.com/images/logos/ZendFramework-logo.png)

> ## Simple Demo Web and Mobile project with REST Full API 
>
> This project is no longer maintained.
>
> At this time, the repository has been archived, and is read-only.
### (c) Denis Puzik <scorpion3dd@gmail.com>

---

The project is written in Zend Framework 2 Release.


RELEASE INFORMATION
===================

Zend Framework 2 Release.

SYSTEM REQUIREMENTS
===================

Zend Framework requires PHP 7.3 or later. 

Please see our reference guide for more detailed system requirements:

http://framework.zend.com/manual/en/requirements.html

1. Web Servers (example Apache or Nginx)
2. Apache Lucene
3. MongoDB
4. MySql
5. PHP

INSTALLATION
============

1. Create DB zf2_demo_integration
~~~~~~
CREATE DATABASE zf2_demo_integration
CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;
~~~~~~

2. Create DB zf2_demo
~~~~~~
CREATE DATABASE zf2_demo
CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;
~~~~~~

3. Create tables in the zf2_demo database by executing the SQL script:

~~~~~~
CREATE TABLE `zf2_demo`.user (
  id int UNSIGNED NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  email varchar(255) NOT NULL,
  password text NOT NULL,
  birthday date DEFAULT NULL,
  phone varchar(255) DEFAULT NULL,
  address varchar(255) DEFAULT NULL,
  description varchar(1000) DEFAULT NULL,
  type tinyint DEFAULT NULL,
  created datetime DEFAULT NULL,
  updated datetime DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 306,
AVG_ROW_LENGTH = 514,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE `zf2_demo`.user
ADD UNIQUE INDEX idx_email (email);
~~~~~~
~~~~~~
CREATE TABLE `zf2_demo`.log (
  id bigint NOT NULL AUTO_INCREMENT,
  date datetime NOT NULL,
  type int NOT NULL,
  event varchar(1000) NOT NULL,
  e varchar(1000) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 185,
CHARACTER SET utf8,
COLLATE utf8_general_ci;
~~~~~~
~~~~~~
CREATE TABLE `zf2_demo`.chat_messages (
  id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  message varchar(1255) NOT NULL,
  stamp timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 23,
AVG_ROW_LENGTH = 1170,
CHARACTER SET utf8,
COLLATE utf8_general_ci;
~~~~~~
~~~~~~
CREATE TABLE `zf2_demo`.image_uploads (
  id int NOT NULL AUTO_INCREMENT,
  filename varchar(255) NOT NULL,
  thumbnail varchar(255) NOT NULL,
  label varchar(255) NOT NULL,
  user_id int NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 7,
AVG_ROW_LENGTH = 8192,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE `zf2_demo`.image_uploads
ADD UNIQUE INDEX filename (filename);
~~~~~~
~~~~~~
CREATE TABLE `zf2_demo`.uploads (
  id int NOT NULL AUTO_INCREMENT,
  filename varchar(255) NOT NULL,
  label varchar(255) NOT NULL,
  user_id int NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 14,
AVG_ROW_LENGTH = 3276,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE `zf2_demo`.uploads
ADD UNIQUE INDEX filename (filename);
~~~~~~
~~~~~~
CREATE TABLE `zf2_demo`.uploads_sharing (
  id int NOT NULL AUTO_INCREMENT,
  upload_id int NOT NULL,
  user_id int NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 20,
AVG_ROW_LENGTH = 2048,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE `zf2_demo`.uploads_sharing
ADD UNIQUE INDEX upload_id (upload_id, user_id);
~~~~~~
~~~~~~
CREATE TABLE `zf2_demo`.store_products (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  cost float(9, 2) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 7,
AVG_ROW_LENGTH = 2730,
CHARACTER SET utf8,
COLLATE utf8_general_ci;
~~~~~~
~~~~~~
CREATE TABLE `zf2_demo`.store_orders (
  id int NOT NULL AUTO_INCREMENT,
  store_product_id int NOT NULL,
  qty int NOT NULL,
  total float(9, 2) NOT NULL,
  status enum ('new', 'completed', 'shipped', 'cancelled') DEFAULT NULL,
  stamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  first_name varchar(255) DEFAULT NULL,
  last_name varchar(255) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  ship_to_street varchar(255) DEFAULT NULL,
  ship_to_city varchar(255) DEFAULT NULL,
  ship_to_state varchar(2) DEFAULT NULL,
  ship_to_zip int DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 14,
AVG_ROW_LENGTH = 2730,
CHARACTER SET utf8,
COLLATE utf8_general_ci;
~~~~~~

3. Clone a project from the repository
~~~~~~
git clone https://github.com/scorpion3dd/zf2_demo.git ./zf2_demo
~~~~~~
4. Composer install
~~~~~~
Composer install --ignore-platform-reqs
~~~~~~
5. In the file /config/autoload/global.php, if necessary, change the parameters
6. Set environment variables:
~~~~~~
- APPLICATION_ENV
- GOOGLE_CLIENT_ID
- GOOGLE_CLIENT_SECRET
- GOOGLE_PROJECT_ID
- GOOGLE_REDIRECT_URI
- GOOGLE_DEVELOPER_KEY
~~~~~~

6. Create virtual host in you web server
### Web servers setup

#### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

```apache
<VirtualHost *:80>
    ServerName zf2-demo.os
    DocumentRoot /path/to/zf2-demo/public
    <Directory /path/to/zf2-demo/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
        <IfModule mod_authz_core.c>
        Require all granted
        </IfModule>
    </Directory>
</VirtualHost>
```

#### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

```nginx
http {
    # ...
    include sites-enabled/*.conf;
}
```


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zfapp.localhost.conf`
it should look something like below:

```nginx
server {
    listen       80;
    server_name  zf2-demo.os;
    root         /path/to/zf2-demo/public;

    location / {
        index index.php;
        try_files $uri $uri/ @php;
    }

    location @php {
        # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME /path/to/zf2-demo/public/index.php;
        include fastcgi_params;
    }
}
```

Restart the nginx, now you should be ready to go!

7. Reload Web Server (example Apache)
~~~~~~
sudo systemctl restart apache2
~~~~~~
8. Reload Web Server (example Nginx)
~~~~~~
sudo systemctl restart nginx
~~~~~~
9. Give write permissions to directories:
- /data/cache
- /data/logs
- /data/images
- /data/search_index
- /data/uploads
~~~~~~
chmod -R 777 ./data/cache
chmod -R 777 ./data/logs
chmod -R 777 ./data/images
chmod -R 777 ./data/search_index
chmod -R 777 ./data/uploads
~~~~~~

DESCRIPTION OF WEB
============

The web part of this project consists of a public part and an admin panel,
which can only be entered after passing authentication, having entered
the username and password correctly.

In the public area, you can:
- get info about Zend Framework;
- get data from Strore - about products;
- get data from Users in Mobile formate;
- go to the authentication form for the subsequent entry of the username
  and password, if entered correctly - then go to the admin panel.

In the admin area, you can:
- get data from Admin - with Users Manager and Upload manager with the ability to 
add, modify, delete records, upload files and shared them among users;
- get data from Media manager with the ability to view Gallery and upload, 
modify, view, delete images;
- get data from third-party services - such as Google Books with the ability to 
search books by keywords and Albums with Photos with the ability to add, modify, 
delete Albums and upload images to Album, view images;
- perform a simple search using filters;
- enerate new data;
- create a new index for full-text search;
- perform a full-text search on a pre-created index by Lucene;
- send your message to e-mail another users;
- send your message to another users in Group Chat and see messages from other 
users in real time;
- view the entire Store Admin to Manage Products and Manage Orders with a page-by-page breakdown of information output
  in the table, view detailed data, update any record,
  delete one or several records;
- view controls in format HTML 5.

DESCRIPTION OF MOBILE
============

The mobile part of this project consists of a public part 
in format adaptive to mobile smartphones.

In which you can:
- get list data about Users with the ability to view detailed info;
- search users by customer name;

DESCRIPTION OF API REQUESTS
============

1. get all items from the users
~~~~~~
GET /mobile/r1/customers
OUT OK (200):
[
  {
     "id":"1",
     "name":"admin",
     "email":"admin@gmail.com",
     "birthday":"2000-09-23",
     "phone":"+380988888888",
     "address":"Lviv",
     "description":"admin from Lviv",
     "type":"1",
     "updated":"2021-10-03 01:12:11"
  },
  {
     "id":"2",
     "name":"admin2",
     "email":"admin2@gmail.com",
     "birthday":"2001-11-12",
     "phone":"+380987777777",
     "address":"Kiev",
     "description":"admin from Kiev",
     "type":"1",
     "updated":"2021-10-03 01:12:11"
  }
]
~~~~~~

2. get one item from the users by user name
~~~~~~
POST /mobile/r1/search
{
   "searchinput": "Saige Sipes"
}
OUT OK (200):
[
  {
     "id":"101",
     "name":"admin Saige Sipes",
     "email":"admin101@gmail.com",
     "birthday":"2000-09-23",
     "phone":"+380988888888",
     "address":"Lviv",
     "description":"admin from Lviv",
     "type":"1",
     "updated":"2021-10-03 01:12:11"
  },
  {
     "id":"203",
     "name":"Saige Sipes admin",
     "email":"admin203@gmail.com",
     "birthday":"2001-11-12",
     "phone":"+380987777777",
     "address":"Kiev",
     "description":"admin from Kiev",
     "type":"1",
     "updated":"2021-10-03 01:12:11"
  }
]
~~~~~~

Logging information in the application
============

1. in file
```bash
$ cd /data/logs/logfile.txt
$ tail -n 100 -f /data/logs/logfile.txt
```
2. in MongoDB zf2 collection logs
```bash
db.getCollection('logs').find({timestamp:{$gte:ISODate("2018-10-02"),$lt:ISODate("2018-11-04")}});
```
3. in MySql DB z2.demo table log

# Zend Framework 2 - Skeleton Application Description

## Introduction

This is a skeleton application using the Zend Framework 2 MVC layer and module systems.

## Installation using Composer

The easiest way to create a new Zend Framework 2 project is to use
[Composer](https://getcomposer.org/).  If you don't have it already installed,
then please install as per the [documentation](https://getcomposer.org/doc/00-intro.md).

To create your new Zend Framework project:

```bash
$ composer create-project -sdev zendframework/skeleton-application path/to/install
```

## Development mode

The skeleton ships with [zf-development-mode](https://github.com/zfcampus/zf-development-mode)
by default, and provides three aliases for consuming the script it ships with:

```bash
$ composer development-enable  # enable development mode
$ composer development-disable # disable development mode
$ composer development-status  # whether or not development mode is enabled
```

You may provide development-only modules and bootstrap-level configuration in
`config/development.config.php.dist`, and development-only application
configuration in `config/autoload/development.local.php.dist`. Enabling
development mode will copy these files to versions removing the `.dist` suffix,
while disabling development mode will remove those copies.

Development mode is automatically enabled as part of the skeleton installation process. 
After making changes to one of the above-mentioned `.dist` configuration files you will
either need to disable then enable development mode for the changes to take effect,
or manually make matching updates to the `.dist`-less copies of those files.

## QA Tools

This project has a QA tooling, with configuration for each of:

- [phpcs](https://github.com/squizlabs/php_codesniffer)
- [phpstan](https://phpstan.org)
- [phpmd](https://phpmd.org)
- [phpunit tests](https://phpunit.de)

Provide aliases for each of these tools in the Composer configuration:

```bash
# Run CS checks:
$ composer cs-check
# Fix CS errors:
$ composer cs-fix
# Run Stan check:
$ composer stan-check
$ phpstan analyse --level=7 --memory-limit=1024M ./module/Application/src/  ./module/A
dmin/src/  ./module/Mobile/src/
# Run Mess Detector check:
$ composer phpmd-app
$ phpmd module/Application/src/ html phpmd_ruleset.xml --reportfile __tests__/phpmd-app.html --suffixes php,phtml
$ composer phpmd-admin
$ phpmd module/Admin/src/ html phpmd_ruleset.xml --reportfile __tests__/phpmd-admin.ht
ml --suffixes php,phtml
$ composer phpmd-mobile
$ phpmd module/Mobile/src/ html phpmd_ruleset.xml --reportfile __tests__/phpmd-mobile.
html --suffixes php,phtml
# Run PHPUnit test:
$ composer test
```

## Running PHPUnit Tests

Once testing support is present, you can run the tests using:

```bash
$ ./vendor/bin/phpunit
```


### PHP Unit tests

This project has a complect PHPUnit tests for all:
- Controllers;
- Servicies;
- Repositories;
- Entities;
