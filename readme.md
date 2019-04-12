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
3. Copy the .env.example to .env: `cp .env.sample .env`
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
9.  Done, just go to mybibd.test in your web browser and login using your creds.

## TODO:
- [x] Transaction history
- [ ] Top up your vCard
- [ ] Schedule standing instructions/automate sending payments
- [ ] Save phone numbers, account numbers and DES numbers
- [ ] Top up DST
- [ ] Top up DES

# License 
Copyright 2019 Izdihar Sulaiman

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# Disclaimer
Use at your own risk, be sure to run this in a secured environment.