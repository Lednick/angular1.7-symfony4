1) composer install
2) Copy .env to .env.local and place your host, user, password for your postgresql
3) bin/console do:da:create
4) bin/console do:sch:update --force
5) bin/console serv:start

Important! Server should be started at 8000 port because url in frontend hardcoded.

Also you should run server for frontend

In project dir
1) cd frontend/
2) php -S localhost:8001