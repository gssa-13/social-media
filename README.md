# Documentacion de Social Media

Tabla de contenido
-----------------
* [Requerimientos](#requerimientos)
* [Instalación](#instalación)
* [Docker](#docker)
* [Servidores Locales](#servidores-locales)


Requerimientos
------------

* PHP ^8.0
* Composer ^2
* Nodejs ^17

Instalación
------------

Existen dos formas para poder ejecutar la aplicacion, la primera es contar con una instalacion
de Docker para la cual es necesario copiar el archivo `.env.example` y renombarlo en `.env` la cual ya
cuenta con la mayoria de las configuraciones necesarias para que el contenedor funcione correctamente en
a excepcion de las notificaciones en tiempo real, para la cual es necesario tener una cuenta en pusher
y registrar las llaves de acceso y el servidor que se haya seleccionado para asi poder correr la 
aplicacion ejecute el siguiente comando desde el directorio donde ha descargado el repositorio

Docker
------------
```shell
docker-compose up -d
```
Este comando descargara las imagenes y construira los contenedores necesarios para ejecutar la aplicacion
para verificar los contenedores en ejecucion
```shell
docker ps
```
Mostrara algo similar a lo siguiente
```shell
CONTAINER ID   IMAGE                    COMMAND                  CREATED          STATUS                   PORTS                                            NAMES
934fea1f5707   sail-8.0/app             "start-container"        1 minutes ago    Up 1 minutes             0.0.0.0:80->80/tcp, 8000/tcp                     docker_laravel.test_1
1aef565dde17   redis:alpine             "docker-entrypoint.s…"   1 minutes ago    Up 1 minutes (healthy)   0.0.0.0:6379->6379/tcp                           docker_redis_1
b03c171be16b   mysql/mysql-server:8.0   "/entrypoint.sh mysq…"   1 minutes ago    Up 1 minutes (healthy)   0.0.0.0:3306->3306/tcp, 33060-33061/tcp          docker_mysql_1
06ff84fe40b8   mailhog/mailhog:latest   "MailHog"                1 minutes ago    Up 1 minutes             0.0.0.0:1025->1025/tcp, 0.0.0.0:8025->8025/tcp   docker_mailhog_1
```
Accedemos al primer contenedor que tiene la imagen de mysql/mysqk-server:8.0
```shell
docker exec -i -t b03c171be16b bash
```
Y crear la base de datos con el nombre que se defina en el archivo `.env` desde la raiz de donde se haya
clonado el repositorio configurar el alias del comando sail e instalar dependencias composer, npm y
compilar este ultimo
```shell
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
sail composer install
sail npm install
sail npm run dev
```

Para que se pueda realizar la carga de archivos se debe crear un enlace simbólico en `/public/storage` desde `/storage/app/public` que será donde sean almacenados los archivos cargados en cada uno de los distintos catalogos que hagan uso de la herramienta, para ello se ejecuta el siguiente comando:
```shell
sail artisan storage:link
```

```shell
sail artisan migrate

sail artisan db:seed
```
con lo cual seran creada la base de datos y los datos de prueba, para agregar usuarios crear migracion
y editar el `Database\Seeders\DatabaseSeeder.php` agregar las etiquetas, categorias que requiera

Servidores locales
------------
Ejecutar los siguientes comandos
```shell
composer install

npm install & npm run dev

php artisan key:generate

php artisan storage:link
```

El requisito previo para ejecutar los siguientes comandos es tener la base de datos creada previo a la
ejecucion

```shell
php artisan migrate

php artisan db:seed
```
