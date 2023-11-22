<?php

function isValidMoneyFormat($moneyString)
{
    // Define the regular expression pattern for a valid money format
    $pattern = '/^\d+(\.\d+)?$/';

    // Use preg_match to check if the string matches the pattern
    return preg_match($pattern, $moneyString);
}

function isValidFile($file)
{
    $allowedExtensions = ['pdf', 'doc', 'docx'];
    $maxSize = 25 * 1024 * 1024; // 25 MB

    $fileName = $file['name'];
    $fileSize = $file['size'];

    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file type
    if (!in_array($fileExtension, $allowedExtensions)) {
        return false; // Invalid file type
    }

    // Validate file size
    if ($fileSize > $maxSize) {
        return false; // File size exceeds the maximum limit
    }

    return true; // File is valid
}

function isValidContactNumber($number)
{
    // Remove any non-digit characters
    $cleanedNumber = preg_replace('/\D/', '', $number);

    // Validate the cleaned number
    return preg_match('/^[0-9]{11}+$/', $cleanedNumber);
}
