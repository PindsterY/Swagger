<?php
function dd($var)
{
    var_dump($var);
    die();
}

/**
 * Handles all errors
 * @param $errno int Error number
 * @param $errstr string Error message
 * @param $file string File where error happened
 * @param $line string Line where error happened
 * @param $context array error context
 */

function error_handler($errno, $errstr, $file, $line, $context)
{
    ob_clean();
    header('HTTP/1.1 500 Internal Server Error');
    $errors[] = 'There was an error processing your request. Please try again later.';
    $msg = "Error $errno\n<b>$errstr</b>\n$file:$line\n\n<pre>" . print_r($context, 1) . '</pre>';
    $live = (!defined('DEBUG_MODE') || !DEBUG_MODE) ? true : false;
    if ($errno === E_USER_ERROR) {
        echo json_encode(['status' => 400, 'error_message' => $errstr], JSON_PRETTY_PRINT);
    }
    if ($live) {
        if ($errno !== E_USER_ERROR) {
            // Send email about the error to developer
        }
    } else {
        $errors[] = $msg;
        $errors[] = 'GET: ' . '<pre>' . print_r($_GET, 1) . '</pre>';
        $errors[] = 'POST: ' . '<pre>' . print_r($_POST, 1) . '</pre>';
        $errors[] = 'COOKIE: ' . '<pre>' . print_r($_COOKIE, 1) . '</pre>';
        $errors[] = 'BACKTRACE: ' . '<pre>' . print_r(debug_backtrace(), 1) . '</pre>';
        var_dump($errors);
    }
    exit();
}

/**
 * Validate a Gregorian date string
 * @param $date string Date to be validated
 * @return bool True if $date was valid date
 */
function valid_date($date)
{
    // Create date object from $date
    $d = DateTime::createFromFormat('Y-m-d', $date);

    // Validate that $d is not false and format method returns the same original date
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * @param $code
 * @param $message
 */
function output_error($code, $message)
{
    header("HTTP/1.1 $code $message");
    echo json_encode(['code' => $code, 'message' => $message]);
    exit();
}