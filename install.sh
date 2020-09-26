cp example.env .env
cp composer.example.json composer.json
composer update
mkdir logs
cd logs
touch access.log error.log
