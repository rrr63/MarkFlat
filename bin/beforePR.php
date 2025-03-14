<?php

exec('./vendor/bin/phpstan analyse -l 6 tests src');
exec('./vendor/bin/phpunit');
exec('./vendor/bin/php-cs-fixer fix src');
exec('./vendor/bin/php-cs-fixer fix tests');