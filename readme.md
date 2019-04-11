WIP - BIBD App Thing

I don't know markdown, so just gonna type it like this.

Anyway, if you're interested in BIBD's SOAP Based APIs, checked the BIBDController.

If you want to run this on your own laptop, use Laragon (assuming you're on windows), steps are:

1) Copy this project into the web directory.
2) Create a database called mybibd.
3) Copy the .env.example to .env `cp .env.sample .env`
4) Then change these variables accordingly (your bibd creds)
USERNAME=null
PASSWORD=null
INTERNET_PIN=null
MOBILE_PIN=null
5) Run php artisan key:generate
6) Run php artisan migrate:fresh --seed
7) Done, just go to mybibd.test in your web browser and login using your creds.

TODO:
- Transaction history
- Schedule standing instructions
