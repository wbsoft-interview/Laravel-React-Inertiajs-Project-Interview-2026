# Hotel-Managemnt-System

- First you should download the project.
- Please setup your composer file by command.
	```
	composer install
	```

- Than please duplicate the .env.example file -> .end file and set the database name
	```
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=database_name_here
	DB_USERNAME=root
	DB_PASSWORD=
	```
- Than you should setup authentication of laravel default auth..
- And now please migrate the all of the migration file...
	```
	php artisan migrate
	```
    
- After completing the all of the setup than you use seeder.....
- You should data seed by this command.
	```
	php artisan db:seed
	```
- Now please generate the key...
	```
	php artisan key:generate
	```	
 - Now please install passport access token...
	```
	php artisan passport:client --personal
	```	
 - If need to generate key...
	```
	php artisan passport:keys
	```	
- Now for server run...
	```
	php artisan serve
	```
- For accessing the admin panel..
    ```
    Super-Admin: mywbpanel@gmail.com
    Passowrd: Dinajpur@2021
    ```
    Fresh Seed
    ```
    php artisan migrate:fresh --seed
    ```



###### Thanks.....		
# Mastering-Panel-12
