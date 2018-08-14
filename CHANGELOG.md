# Changelog
All notable changes to this **service-carts** project will be documented in this file.

## [1.0.0] - 2017-08-22
### Added
- sh script for create databases mongo `./docker/mongodb/set_mongodb_password.sh`
- mongodb container
- php lib zip bcmath mcrypt mbstring pdo_mysql curl intl
- pecl lib php-mongodb
- added delayed queue support for abandoned carts and plugin for rabbit rabbitmq_delayed_message_exchange
- GET /v1/carts/my - added api call
- GET /v1/carts/my/:cartId - added api call
- GET /v1/carts/:cartId - added api call
- POST /v1/carts - added api call
- POST /v1/carts/:cartId/clone - added api call
- PUT /v1/carts/:cartId/close - added api call
- PUT /v1/carts/:cartId - added api call
- LINK /v1/carts - added api call (for products, services, memberships)
- LINK /v1/carts/:cartId/:itemType/:itemId/:innerItemType - added api call (for products, services, memberships)
- UNLINK /v1/carts - added api call (for products, services, memberships)
- UNLINK /v1/carts/:cartId/:itemType/:itemId/:innerItemType - added api call (for products, services, memberships)
- added usage of yii2tech/embedded

#### Doc
The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/).
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).
