## Installation

First clone this repository. Navigate to root of the project and then

<pre>
    <code>composer install</code>
</pre>

Copy the contents of .env.example to .env file.

<pre>
    <code>php artisan migrate</code>
    <code>php artisan db:seed --class=CountrySateSeeder</code>
</pre>


Isnitiate your server on a new terminal

<pre>
    <code>php artisan serve</code>
</pre>

Now, Go to your web browser, open the given URL


 <pre>
<code>http://localhost:8000/employee-ajax-crud</code>
</pre>