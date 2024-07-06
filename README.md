## Event Management API

## Features:

1.  Laravel 11.0
2.  REST Full API's -> Passport
3.  Database: MySql

## Installation Instructions

### Step 1: Install Laravel

1. **Install Composer**

2. **Create a Laravel Project**:

    ```bash
    composer create-project --prefer-dist laravel/laravel event-management-api
    ```

    ## change directory

    ```bash
    cd event-management-api
    ```

## Step 2: Set Up Environment

.env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=root
DB_PASSWORD=your_password

## Step 3: change file config/database.php

    'default' => env('DB_CONNECTION', 'mysql'),

    'mysql' => [
    'driver' => 'mysql',
    'database' => env('DB_DATABASE', 'laravel'),
    'collation' => env('DB_COLLATION', 'utf8mb4_general_ci'), ]

## 6. Kkey Generate

```bash
  php artisan key:generate

```

## 7. Database Ccreate

    event_management

## 8. Passport Install

    ```bash
    php artisan install:api --passport
    ```

## 9. Ssetup user model App/Models/User.php

    use Laravel\Passport\HasApiTokens;
    class User extends Authenticatable
    {
        use HasApiTokens, HasFactory, Notifiable;
    }

## Setup config/auth.php

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

## 10. Passport Key Generate

```bash
   php artisan passport:keys

```

## 11. Run vendor:publish

    ```bash
    php artisan vendor:publish --tag=passport-config

    ```

## 12. Run Install Passport Client

     Copy and pased client id and screet .env file

        PASSPORT_PASSWORD_CLIENT_ID =  'this'
        PASSPORT_PASSWORD_SECRET =  'this'
        OAUTH_TOKEN_URL = 'base-url/public/oauth/token'

## 13. Publish

    ```bash
    php artisan vendor:publish --tag=passport-views

````

## 14. Run command

    ```bash
    php artisan config:cache
    ```

    ```bash
    php artisan config:clear
    ```

    ```bash
    php artisan config:optimize
    ```

## 15. Start local server

    ```bash
    php artisan serve

    ```


## 16. Setup Mail Notification
     Register and login mailtrup account and copy nad paste .env file laravel 8+ mail testing script.

    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=null
````
