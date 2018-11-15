# WEB DIGESA

### Requerimientos

* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/) v10+ to run.
* [Laravel](https://laravel.com/) version 5.7+
* [PHP](http://php.net/) version 7.1+
* [SQL Server](https://www.microsoft.com/es-es/sql-server) Sql Server 2008+

### Instalacion

Install the dependencies and devDependencies and start the server.

```sh
$ git clone https://gitlab.com/Developer-HNAL/sigesaweb.git websigesa
$ cd websigesa
$ cp .env.example .env
```

Modifican los sigueintes parametros del archivo .env recien creado.

```sh
DB_CONNECTION=sqlsrv
DB_HOST=HNAL-DESA1-PC
DB_PORT=1433
DB_DATABASE=SIGH
DB_USERNAME=admin
DB_PASSWORD=123456
```

Instalamos las dependencias y generamos el app key:
```sh
$ composer update
$ php artisan key:generate
$ npm install
$ npm run dev
```


### Desarrolladores

 - Rodolfo Crisanto Rosas (crisanto.rosas.22@gmail.com)
 - Luis Gonzales Navarro (luisgonzalesn@hotmail.com)
 

### License

Copyright © JJ INGENIEROS