install:
	composer install
test: install
	phpunit --configuration tests/phpunit.xml.dist
