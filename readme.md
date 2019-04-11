# WIP - BIBD App Thing

I don't know markdown, so just gonna type it like this.

Anyway, if you're interested in BIBD's SOAP Based APIs, check the BIBDController (`/app/Http/Controllers/BIBDController.php`).

If you want to run this on your own laptop, use Laragon (assuming you're on windows) or Valet (if you are on Mac), steps are:

1. Copy/Clone this project into the web directory.
2. Create a database called mybibd.
3. Copy the .env.example to .env: `cp .env.sample .env`
4. Then change these variables accordingly (your bibd creds). Note: `PASSWORD` and `INTERNET_PIN` values should be the same and modify the database (`DB_`) according to your environment setup.

    ```
    DB_DATABASE=mybibd
    ...
    USERNAME=null
    PASSWORD=null
    INTERNET_PIN=null
    MOBILE_PIN=null
    ```
5. Run `composer install`
6. Run `npm install`
7. Run `php artisan key:generate`
8. Run `php artisan migrate:fresh --seed` (Note: The seeder will add `Iz S.` as the name of the user)
9. Done, just go to mybibd.test in your web browser and login using your creds.

## TODO:
- Transaction history
- Schedule standing instructions

# License 
Copyright 2019 IZDIHAR SULAIMAN

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
