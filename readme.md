commands:

Build containers

`docker-compose build`

_Make sure you stop all services that use port 8080_

Run containers

`docker-compose up -d`

Create database

`docker exec -it client-server-php php bin/console doctrine:database:create`

Now you able to visit server app using `http://localhost:8080`

You can go to `client` folder with `cd client`

You can use next commands

`php bin/console api:server-group --help`

`php bin/console api:server-user --help`