Task:
Create a ToDo web application (with frontend and backend parts). There have to be the following possibilities:

1. Create a ToDo list
2. View particular ToDo list
3. List all ToDo lists

Every ToDo can have a list of items. There has to be the possibility to add items to ToDo list, remove items from ToDo list. But it is not needed to update items in ToDo list.

Also, it is not needed to update ToDo list itself (title for example) - just create, view and list all ToDo lists as I mentioned above.

But in addition we need to implement the searching feature:

4 Search in all ToDo lists items (for example if there are two ToDos: First ToDo (items: Some item, Another item, Third Item) and Second Important ToDo (items: Text, Another item name, Some more item) - if I'm searching for "some" I have to get both ToDos in search results, as in each ToDo list exist Item with word "some" ("Some item" in First ToDo and "Some more item" in Second ToDo).

For the frontend, you can use Bootstrap and should use AngularJS 1.7. Frontend part should send and receive ToDos data from the backend REST API app, built with Symfony 4 framework and FosRestBundle.

How-to start project:

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