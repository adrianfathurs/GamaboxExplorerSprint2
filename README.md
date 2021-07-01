# DataPortal
Installation
Given below are the steps you need to follow, to install the vuexy-laravel-starter on your system:


Step 1: Open the terminal in your root directory(vuexy-laravel-starter) & to install the composer packages run the following command:

<code>composer install</code>

Step 2: In the root directory, you will find a file named .env.example rename the given file name to .env and run the following command to generate the key (and you can also edit your data base credentials here)

<code>php artisan key:generate</code>

Step 3: By running the following command, you will be able to get all the dependencies in your node_modules folder:

<code>npm install</code>

Step 4: To run the project you need to run following command in the project directory. It will compile the vue files & all the other project files. If you are making any changes in any of the .vue file then you need to run the given command again.

<code>npm run dev</code>

Step 5: To serve the application you need to run the following command in the project directory. (This will give you an address with port number 8000)

Now navigate to the given address you will see your application is running.

<code>php artisan serve</code>

To change the port address, run the following command:

<code>php artisan serve --port=8080</code>    // For port 8080

<code>sudo php artisan serve --port=80</code> // If you want to run it on port 80, you probably need to sudo.

Watching for changes: Running npm run dev every time you make changes to file is inefficient. Hopefully there's command so your changes can be watched and get reflected accordingly.

<code>npm run watch</code>

----------------------------------------------------------------------------------------------------------------------------------------------------------------

To activate JWT run the program again with another port, --port=8001

<code>php artisan serve --port=8001</code>


if you run local, you have to set app/config/app.php :

 ```

   'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

```
