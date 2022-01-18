<?php
$outputFile = '.env';
$env = [];

foreach ($_SERVER as $key => $value) {
    if (str_starts_with($key, 'ENV_')) {
        $env[] = substr($key, 3) . '=' . $value;
    }
}

echo join(PHP_EOL, $env);