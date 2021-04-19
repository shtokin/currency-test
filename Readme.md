
##Run

`docker-compose up -d`

##To install composer dependencies

`docker exec -it curve-php /bin/bash`

then

`composer install`

## To run tests  
`php ./vendor/bin/phpunit`