<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__ . '/../storage/framework/maintenance.php')) {
    require __DIR__ . '/../storage/framework/maintenance.php';
}

/**
|--------------------------------------------------------------------------
| If this is instance has a vcap directory, application should assume
| this is a CloudFoundry instance. An AWS credentials file is required
| with appropriate ID and Secret gleaned from the environment vars
|--------------------------------------------------------------------------
 */
if(is_dir('/home/vcap') && !file_exists('/home/vcap/.aws')) {
    file_put_contents('/app/.aws/credentials', "
[{$_SERVER['AWS_IAM_USER']}]
aws_access_key_id = {$_SERVER['AWS_ACCESS_KEY_ID']}
aws_secret_access_key = {$_SERVER['AWS_SECRET_ACCESS_KEY']}
    ");

    `ln -s /app/.aws /home/vcap/.aws`;
}


/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
