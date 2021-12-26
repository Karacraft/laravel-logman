# Karacraft Logman
A simple Model Event Logging Package  


## Usage

**Installation**  

    composer require karacraft/logman

**Migrate**

    php artisan migrate

**Publish**

    php artisan vendor:publish --provider="Karacraft\Logman\LogmanServiceProvider" --tag="logman-migrations"
    php artisan vendor:publish --provider="Karacraft\Logman\LogmanServiceProvider" --tag="logman-traits"

**Setup**  
Add Trait EventLogger to your models, where you want to start logging  


