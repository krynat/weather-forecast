#!/usr/bin/env bash

php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --force
apache2-foreground