## Installation

First clone this repository. Navigate to root of the project and then

<pre>
    <code>composer install</code>
</pre>

Copy the contents of .env.example to .env file.

<pre>
    <code>php artisan key:generate</code>
    <code>php artisan migrate</code>
    <code>php artisan db:seed --class=CountrySateSeeder</code>
</pre>


Isnitiate your server on a new terminal

<pre>
    <code>php artisan serve</code>
</pre>

 