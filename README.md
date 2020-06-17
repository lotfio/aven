<p align="center">
 
<img src="https://user-images.githubusercontent.com/18489496/38557718-c7fa6c12-3cc5-11e8-99f9-69e923e24ace.png" align="center" alt="logo" title="logo">


</p>
<p align="center">
    <a href="#">
        <img src="https://img.shields.io/badge/Licence-MIT-f6ca19.svg" alt="LICENSE" title="LICENSE">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/PHP-7-3498db.svg" alt="PHP 7" title="PHP 7">
    </a>    
    <a href="#">
        <img src="https://img.shields.io/badge/version-0.4.0-27ae60.svg" alt="version" title="version">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/coverage-50%25-95a5a6.svg" alt="coverage" title="coverage">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/downloads-1k-ff5722.svg" alt="downloads" title="downloads">
    </a>
 <a href="#">
        <img src="https://img.shields.io/badge/build-passing-e91e63.svg" alt="build" title="build">
    </a>
<p align="center">
    <strong>:tractor: Robust PHP router :tractor:</strong>
  </p>
</p>

### :fire: Introduction :
 <b>Aven</b> (ayven) is a robust and <b>flexible</b> PHP router For PHP7 and newer versions.

### :pushpin: Requirements :
- PHP 7.2 or newer versions
- PHPUnit >= 8 (for testing purpose)

### :rocket: Installation & Use :
```php
    composer require lotfio/aven
```


### :pencil2: Usage :
**1- Quick use :** 

```php
 <?php
    require_once 'vendor/autoload.php';

    // here we are using $_SERVER['REQUEST_URI']
    // you can use $_GET['uri']
    $router = new Aven\Router($_SERVER['REQUEST_URI']);
 
    $router->get('/', function(){  // with a callback 
        return "welcome from aven";
    });

    $router->get('/', "UsersController@method"); // controller method
    $router->get('/', "UsersController::method"); // controller static method


    $router->init(); // initialize router 
```
**2- Available routes :** 
* `GET`, `POST`, `ANY`, `PUT`, `DELETE`, `HEAD` 
```php
 <?php

    $router->get('/',  function(){ return " this is get method"; }); 
    $router->post('/', function(){ return " this is post method"; });
    $router->any('/',  function(){ return " this is any method allows all"; });

    $router->put('/',    function(){ return " this is put method. you should send $_POST['_method'] = 'put'"; }); 
    $router->delete('/', function(){ return " this is delete method. you should send $_POST['_method'] = 'delete'"; }); 
    $router->head('/',   function(){ return " this is head method. you should send $_POST['_method'] = 'head'"; }); 
```

**3- named routes :**
```php
 <?php
    $router->get('/',  function(){ return "this is get named route (default)";})->name('default');
```

**4- redirects :**
* `$router->redirect(string $routeName, array $params = [], int $httpcode = 301)`
```php
 <?php
    // route 1 
    $router->get('/',  function() use($router){  // accessing this route will redirect you to route2 means /hola

        $router->redirect('route2'); // if parametrised route you can pass array of parameters

    })->name('default');

    // route 2
    $router->get('/hola',  function(){ return " welcome to hola from default route";})->name('route2');
```

**5- route parameters :**
* you can use both parenthesis or curly braces for parameters 
* predefind parameters:
    - `:int`, `:integer`, `:num`, `:numeric`, `:number` = **\d+**
    - `:str`   = **\w+**
    - `:alpha` = **[A-z]+**
```php
 <?php

    $router->get('/test/(:int)',  function(){}); // evaluates to /test/\d+
    $router->get('/test/(:str)',  function(){}); // evaluates to /test/\w+

    // optional parameters (if optional parameter uri should end with /)
    $router->get('/test/(:id*)',  function(){}); // optional id /test/ or /test/1
    $router->get('/test/(:id?)',  function(){}); // zero or one id /test/ or /test/0-9
    

```
**6- custom route parameters :**
* `->regex(array $params)`
```php
 <?php
    // override predefined param
    $router->get('/test/(:str)',  function(){})->regex(array(":str"=> '[my-reg-ex]'));

    // custom param
    $router->get('/test/(:hola)',  function(){})->regex(array(":hola"=> '[my-reg-ex]'));

```
**7- route groups :**
* `$router->group(string $uri,callable $callback, ?string $groupName)`
- you can have as many nested groups as you want
```php
 <?php
   
   $router->group('/mygroup', function($router){  // groups adds prefixes to routes

        $router->get('/test',  function(){ return "from /mygroup/test" }); // evaluates to /mygroup/test

   });

    // multiple groups
    $router->group('/group1', function($router){  

        $router->group('/group2', function($router){  

            $router->get('/test',  function(){ return "from /group1/group2/test" }); // evaluates to /group1/group2/test
        });
   });


```
**8- additionl routes :**
* `$router->form(string $uri, $callback|$class, ?array $override, ?string $routeName)`
```php
 <?php
    // form route with callback
    $router->form('/login', function(){  }); // works both with GET and POST

    // form route with class
    $router->form('/login', Login::class); // by default class should have showForm & submitForm

    // override default form methods 
    $router->form('/login', Login::class, ['get','post']);

    // named form method 
    $router->form('/login', Login::class, ['get','post'], 'login.form');

```
**9- additionl routes :**
* `$router->crud(string $uri, string $class, ?array $only, ?string $routeName)`
```php
 <?php
    // crud route
    // this needs a User class with 4 methods create,read,update,delete
    // create => POST user/create
    // read   => GET with optional pareter user/read/
    // update => PUT with optional pareter user/update/
    // delete => DELETE with optional pareter user/delete/
    $router->crud('/user', User::class);

    // disable some methods 
    $router->crud('/user', User::class, ['c']); // only create
    $router->crud('/user', User::class, ['create']); // only create
    $router->crud('/user', User::class, ['c', 'u']); //  create & update
    $router->crud('/user', User::class, ['create', 'update']); //  create & update

    // named crud
    $router->crud('/user', User::class, NULL, 'myCrud'); //  name can be used for redirections

```

### :computer: Contributing

- Thank you for considering to contribute to ***Package***. All the contribution guidelines are mentioned [here](CONTRIBUTING.md).

### :page_with_curl: ChangeLog

- Here you can find the [ChangeLog](CHANGELOG.md).

### :beer: Support the development

- Share ***Package*** and lets get more stars and more contributors.
- If this project helped you reduce time to develop, you can give me a cup of coffee :) : **[Paypal](https://www.paypal.me/lotfio)**. 💖

### :clipboard: License

- ***Package*** is an open-source software licensed under the [MIT license](LICENSE).