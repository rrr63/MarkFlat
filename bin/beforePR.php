<?php

exec('php ./vendor/bin/phpstan analyse -l 6 tests src');
exec('php ./vendor/bin/phpunit');
exec('php ./vendor/bin/php-cs-fixer fix src');
exec('php ./vendor/bin/php-cs-fixer fix tests');