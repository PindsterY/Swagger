<?php
use Veebiteenus\Application;

require 'vendor/autoload.php';
require 'config.php';
require 'app/functions.php';

// Redirect errors to our own error handler
set_error_handler("error_handler", E_ALL);

// Swagger PUT request compatibility
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    // Return only the headers and not the content
    // Only allow CORS if we're doing a GET - i.e. no saving for now.
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) &&
        $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'GET'
    ) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
    }
    exit;
}

// Launch the app
$app = new Application;
$app->run();