<?php

// Load environment variables from .env file
$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
} else {
    die('.env file not found.');
}

// Example usage
$email = $_ENV['EMAIL'];
$password = $_ENV['PASSWORD'];
$semaphore_api = $_ENV['SEMAPHORE_API'];
