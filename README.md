# Chat

Simple chat application
 
### Prerequisites
 * PHP 7.1
 * ZeroMQ
 * MySQL
 * Composer
 * NPM
 * Bower
 * Gulp
 
### Installation
 
 In root directory run:
 ```
 composer install
 ```
 In web/front run:
 ```
 bower install
 npm install
 gulp less
 ```

### To start the application you need to run:
 Application api front controller:
  ```
  web/front.php;
  ```

 Ratchet server:
 ```
  php bin/PusherServer.php
 ```
