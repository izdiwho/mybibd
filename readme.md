# WIP - BIBD Web App

## Introduction
This laravel web application was done to play around and test the idea of creating a web application that utilizes the existing BIBD Mobile Webservices. On top of that it is also to see the possibilities of extending the features to include:
1) P2P QR Payment for consumer to consumer, instead of just consumer to merchant.
2) Scheduling auto payments or creating standing instructions.
3) A more persistent login
4) And more..

Anyway, if you're interested in BIBD's Webservices, check the BIBDController (`/app/Http/Controllers/BIBDController.php`). You should see how it's implemented in there.

## Installing & Configuration

If you want to run this on your own laptop, use Laragon (assuming you're on windows) or Valet (if you are on Mac), steps are:

1. Copy/Clone this project into the web directory.
2. Create a database called mybibd.
3. Copy the `.env.example` to `.env`: `cp .env.example .env`
4. Then change these variables accordingly (your bibd creds). Note: `PASSWORD` and `INTERNET_PIN` values should not be the same and modify the database (`DB_`) according to your environment setup.
   
   **Make sure you enter your password/pin correctly to prevent getting locked out. If you do get locked out, call BIBD's hotline for help on resetting it.**

    ```
    DB_DATABASE=mybibd
    ...
    USERNAME=null <-- Your BIBD Login ID
    PASSWORD=null <-- A random password to login to this web app
    INTERNET_PIN=null <-- Your BIBD internet pin
    MOBILE_PIN=null <-- Your BIBD mobile pin
    ```


5. Run `composer install`
6. Run `npm install`
7. Run `php artisan key:generate`
8. Run `php artisan migrate:fresh --seed`
9.  Done, just go to http://mybibd.test in your web browser and login using your creds.

## Installing & Configuration The Docker Way :whale:

Must have Docker and docker-compose installed. This method uses [Laradock](https://laradock.io/getting-started/#Usage) as follows:

1. Copy/Clone/Download this project repo.
2. Copy the `.env.example` to `.env`: `cp .env.example .env`
3. Then change these variables accordingly (your bibd creds). Note: `PASSWORD` and `INTERNET_PIN` values should not be the same and modify the database (`DB_`) according to your environment setup.
   
   **Make sure you enter your password/pin correctly to prevent getting locked out. If you do get locked out, call BIBD's hotline for help on resetting it.**

    ```
    DB_DATABASE=mybibd
    ...
    USERNAME=null <-- Your BIBD Login ID
    PASSWORD=null <-- A random password to login to this web app
    INTERNET_PIN=null <-- Your BIBD internet pin
    MOBILE_PIN=null <-- Your BIBD mobile pin
    ```

4. Enter the laradock folder and copy `env-example` to `.env`

`cd laradock`
`cp env-example .env`

You can edit the `.env` file to choose which software’s you want to be installed in your environment. You can always refer to the `docker-compose.yml` file to see how those variables are being used.

Depending on the host’s operating system you may need to change the value given to `COMPOSE_FILE`. When you are running Laradock on Mac OS the correct file separator to use is `:`. When running Laradock from a Windows environment multiple files must be separated with `;`.

By default the containers that will be created have the current directory name as suffix (e.g. `mybibd_workspace_1`). This can cause mixture of data inside the container volumes if you use laradock in multiple projects. In this case, either read the guide for [multiple projects](https://laradock.io/getting-started/#B) or change the variable `COMPOSE_PROJECT_NAME` to something unique like your project name.

*Note: Sometimes the `.env` file is changed/edited doesn't get reflected into the container, and if that happens, you have to rebuild the containers. `docker-compose down`, `docker-compose up -d nginx mysql`, then redo from step 6*

5. Build the environment and run it using `docker-compose`

In this example we’ll see how to run NGINX (web server) and MySQL (database engine) to host a PHP Web Scripts:

`docker-compose up -d nginx mysql`

Note: All the web server containers `nginx`, `apache` ..etc depends on `php-fpm`, which means if you run any of them, they will automatically launch the `php-fpm` container for you, so no need to explicitly specify it in the up command. If you have to do so, you may need to run them as follows: `docker-compose up -d nginx php-fpm mysql`.

You can select your own combination of containers from [this list](https://laradock.io/introduction/#supported-software-images).

(Please note that sometimes we forget to update the docs, so check the `docker-compose.yml` file to see an updated list of all available containers).

6. Enter the Workspace container

`docker-compose exec workspace bash`

*Alternatively, for Windows PowerShell users: execute the following command to enter any running container:*

`docker exec -it {workspace-container-id} bash`

Note: You can add `--user=laradock` to have files created as your host’s user. Example:

`docker-compose exec --user=laradock workspace bash`

*You can change the PUID (User id) and PGID (group id) variables from the `.env` file)*

7. Execute commands

Run `composer install && npm install && php artisan key:generate && php artisan migrate:fresh --seed`

8. Open your browser and visit your localhost address.

http://mybibd.test



## TODO:
- [x] Transaction history
- [x] Top up your vCard
- [ ] Schedule standing instructions/automate sending payments
- [ ] Save phone numbers, account numbers and DES numbers
- [ ] Top up DST
- [ ] Top up DES
- [ ] Upgrade Laravel and MySQL

# License 
Copyright 2019 Izdihar Sulaiman

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# Disclaimer
Use at your own risk, be sure to run this in a secure environment.
