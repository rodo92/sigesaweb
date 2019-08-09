# WEB SIGESA

### Requerimientos

* [Composer](https://getcomposer.org/) v1.7.2+
* [Node.js](https://nodejs.org/) v10+ to run.
* [Laravel](https://laravel.com/) version 5.7+
* [PHP](http://php.net/) version 7.1+
* [SQL Server](https://www.microsoft.com/es-es/sql-server) Sql Server 2008+
* [Git](https://git-scm.com/)

### Instalacion
```sh
$ git clone https://gitlab.com/Developer-HNAL/sigesaweb.git websigesa
$ cd websigesa
$ cp .env.example .env
```

Modifican los siguientes parámetros del archivo .env recien creado.

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
### Otras configuraciones
```sh
--- Actualizaciones de BD ---
ejecutar el archivo /database/actualizaciones.sql
* leer el archivo antes de ejecutar

--- Configuraciones para GIT ---
$ git config --global user.name 'Su Nombre'
$ git config --global user.email 'su email'
$ git config --global core.autocrlf false
$ git config --global core.safecrlf false
```


### Desarrolladores

  Rodolfo Crisanto Rosas (crisanto.rosas.22@gmail.com)
  Luis Gonzales Navarro (luisgonzalesn@hotmail.com)
 

### License

MIT
