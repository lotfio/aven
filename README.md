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
        <img src="https://img.shields.io/badge/version-0.2.1-27ae60.svg" alt="version" title="version">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/build-passing-e91e63.svg" alt="build" title="build">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/coverage-50%25-95a5a6.svg" alt="coverage" title="coverage">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/downloads-1k-ff5722.svg" alt="downloads" title="downloads">
    </a>
</p>

## Introduction :
<b>Aven</b> (ayven) is a robust and <b>flexible</b> PHP router For PHP7 and newer versions.

## Features : 
  * Flexibility (Route calling as a `Facade` or as an `Object`).
  * More than 14 `HTTP VERBS` `GET`,`POST`,`PUT`,`PATCH`,`DELETE`,`OPTION`,`PURGE`,`HEAD`,`COPY`
  * Name it what ever you want `Aven`,`MyRouter`,`Banana`,`DonaldTrump`.
  * Regular Expressions Filters `filter()`.
  * `Aven` CLI (Command Line Tool).
  * Routes Listing.
  * Routes caching to speed up your application on production.
  * callback call, controller method call, static method call.
  * Rerun data formating (arrays and objects are encoded to json by default).

## Install it :

```php
  composer require lotfio/aven
```

## Configure your web server :
You should redirect all requests to a front page `index.php`for example

**APACHE :**

```php
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**NGINX :**

```php
    location / {
        try_files $uri /$uri /index.php?$query_string;
    }
```

## use it :

**1- Quick use :** use `Aven Facade`

  - With a callback :

```php
 <?php
  // autoload composer
    require_once 'vendor/autoload.php';
  
    use Aven\Facades\Aven;
   
    // get with a callback
    Aven::get("/", function(){  
        return "Hello from Aven this is base route";    
    });
 
    Aven::init(); // initialize router 
```

**Note :**

Don't forget to `Aven::init()` initialize the router, if you are using the router in your custom framework you can move the `Aven::init()` statment to the Kernel to clean up your routes file.

* With a controller method :

```php
    // get with a controller method
    Aven::get("/index", "IndexController@indexMethod");
```

* With a static controller method :

```php   
    // get with a controller static method
    Aven::get("/index", "IndexController::indexMethod");
```

* If you use namespaces you can prepend the controller name or use `Aven::config` to set the base namespace :
 
```php
     // get with a controller method
    Aven::get("/index", "YourNamespace\IndexController@indexMethod");
```

* Or :

```php
    Aven::config([
        "namespace" => "Your\\Namespace\\"
   ]);
```

**Available `HTTP` verbs :**

`GET`,`POST`,`PUT`,`PATCH`,`DELETE`,`COPY`, `HEAD`,`OPTIONS`,`LINK`,`UNLINK`,`PURGE`,
`LOCK`,`UNLOCK`,`PROPFIND`,`ANY`

**Note :**

As HTML supports only the `GET` and `POST` from methods it is handy to append a hidden input to your form named `_method` to be able to use other `HTTP METHODS` and to surpass this limitation :

```html
  <form action="" method="POST">
      <input type="hidden" name="_method" value="PUT">
  </form>
```


**Route parameters :**

```php  
  Aven::get("/users/{id}", function($id){
      echo " get users by id " . $id;
  });
 
```
  
**Route parameters filters:**

You can attach a `filter()` method to your routes

```php
 Aven::get("/users/{id}", function($id){
      echo " get users by id " . $id;
  })->filter(["id"=>"/[0-9]+/"]);
```
 

**2- Custom use :**

* To customise the router name create a custom router class and extend the base Facade like this:

```php 
   
   class MyRouter extends Aven\Facades\Facade{} // custom router class 
    
   MyRouter::get("/", function(){
      return "Hello from my custom router";
   });
    
```

* Or You can simply give an alias to the base `Facade` :

```php
use Aven\Facades\Facade as MyRouter;
```
## Aven Console (CLI) :

Aven CLI is a small tool aims to help you during development:

![aven](https://user-images.githubusercontent.com/18489496/39255419-61873344-48a4-11e8-9b88-a163d52b388a.gif)

**configure Aven CLI**

make sure to :
 - Include **composer autoload file** `vendor/autoload.php` in your `vendor/bin/aven` executable
 - And include your routes file in the same file.
 - You also can symlink ar copy `vendor/bin/aven` to your project root `.` or `/usr/local/bin` to make it globally executable.

Your `vendo/bin/aven` should have these two lines:

```php
require '../autoload.php';
require '../../routes/myRoutesFile.php'; // routes file

```

**Available Commands :**

Assume we have moved `vendor/bin/Aven` to `../../` our project location and we have added the routes and the autoload files.

**1-routes :** this command will list all defined routes giving you the ability to debug and see your routing table : 

![routes](https://user-images.githubusercontent.com/18489496/39255488-90e4e46a-48a4-11e8-9fd9-b0ea6ed26a49.gif)

**2-Caching :**

Cashing is very important to speed up any web application therefore `Aven` helps you to cache your routes and load from cache during production which increases your application speed. 
By default `Aven` doesn't allow `Closure` caching which is the default behavoir of PHP However if you feel that you need to cache Closures **Which is not recomended** consider using [**SuperClusore**](https://github.com/jeremeamia/super_closure) package.

**Setting up caching location :**

```php
 Aven::config([
    "cache" => __DIR__ . "/cache"
]);
```

**cache routes**

![cache](https://user-images.githubusercontent.com/18489496/39255518-ab05210c-48a4-11e8-8342-9535a5a5bc18.gif)

**clear cache**

![clear](https://user-images.githubusercontent.com/18489496/39255583-cc00c1e0-48a4-11e8-8a91-91328740db97.gif)

## Custom Error Pages
  You can use your custom error pages by defining your custom `set_exception_handler` and translate error codes `$exception->getCode()` to views.

  - `Aven` trhows a `NotFoundException` with `404` error code when no route has been matched or found.
  - and it throws a `RegExMisMatchException` with `500` error code if a regular expression filter has not been matched.
  

## TODO 

**Optional parameters support:**
  
```php
  Aven::get('/page/{id?}');
```

**Adding some useful methods like**

```php
  Aven::group();
  Aven::namespace();
  Aven::form();
```


## Contributing

Thank you for considering to contribute to Aven. All the contribution guidelines are mentioned [here](CONTRIBUTING.md).


## License

Aven is an open-source software licensed under the [MIT license](https://github.com/lotfio/aven/blob/master/LICENSE).
