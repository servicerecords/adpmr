<?php

$options = getopt('', ['env:', 'app:']);
$environment = $options['env'] ?? 'sandbox';
$app = $options['app'] ?? 'active';

if (file_exists('./cf.ini')) {
    $settings = parse_ini_file('./cf.ini', true, INI_SCANNER_RAW);

    foreach ($settings as $key => $value) {
        if (is_array($value) && $environment == $key) {
            foreach ($value as $environmentKey => $environmentValue) {
                setCFEnv($app, $environmentKey, $environmentValue);
            }
        } elseif (!is_array($value)) {
            setCFEnv($app, $key, $value);
        }
    }

    // `cf restage {$app}`;
}

function setCFEnv($app, $key, $value)
{
    if ($value == 'false' || $value == 'true')
        $value = $value ? 'true' : 'false';

    if (strpos($value, ' ') !== false)
        $value = '"' . $value . '"';

    print "{$app} {$key} {$value}" . PHP_EOL;

    `cf set-env {$app} {$key} {$value}`;
}