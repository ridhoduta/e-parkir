<?php

// Show all errors for debugging on Vercel
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the working directory is correct
chdir(__DIR__ . '/../public');

// Forward to CodeIgniter's index.php
require __DIR__ . '/../public/index.php';
