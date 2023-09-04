# READ ME! (Simple-php-MVC)

 A simple (Obviously unfinished and not production ready) pure PHP Model View Controller framework, with a small e-commerce website integrating the core components.
  Built in 12 hours



 
Tested only using the php server in PHPStorm(php -S).

Currently, only a few exceptions are handled, exception handling will be pushed in the future.


Installation:
1. Clone git repo
2. Install composer packages
3. Navigate to public/ and run  php -S localhost:8000 -c <Absolute path to a php.ini that has sqlite driver enabled>

Usage:
- Add routes to public/index.php
- Create controllers in app/controllers
- Create views in app/views
- Create models in app/models
- Controllers should inherit from Core/BaseController or a class that inherits from that one
- app/models (If the object is related to the database) should inherit from Core/Database



Small documentation:
Core subfolder:
- Application.php Class
    This is the main container for the application
- Router.php Class
    Maps the routes to a callback function, string, or will just read a static file
    Add get and post routes with addGetRoute, addPostRoute,
    View public/index.html for example routes with Controller connection
- Database.php
    This is the connection for the database.
    Migrate() runs all migrations in migrations/ subfolder,
    (not yet implemented is the following)
    it will check the migrations table in db for applied migrations
    and only apply the unapplied migrations found in migrations/
- Abstract classes BaseModel and DataBase Model
    - Basemodel currently only has a method to load data in a model
    - Database Model _________________________
    - get method to get a record from the db, returns object, or associative array,
    - filter method to filter records with specific value from the db, returns associative array,
    - delete method to delete record from db
    - count method to count records of type of object
    - all method to retrieve all objects, optional pagination implemented in app/services/Paginator, and $asArray param default=false to specify if you want objects or assoc arrays
    - save() method to save.
- CartSession.php Class
    - Offers methods to easily manage cart session and cartitems
- ApiController.php Class
    -  This is not a core class!
    - Offers some methods for easier api backend, but currently it's more of a wrapper class for the cartSession class

  Create your routes in public/index.php
  Match them to controllers in app/controllers (view existing controllers for more details)
  Render template with twig or return a JsonResponse.
  Also Exceptions can be returned and printed (don't do this for api calls as it will not work currently).
  Create models inheriting the DatabaseModel and set their getTableName method or you will get an exception
  

There is also an option for middlewares but not implemented in the router,
middlewares can be created in middlewares/, they should be mapped to the routes, and their methods called before calling the user_func() in router.
