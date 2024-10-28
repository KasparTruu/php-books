<?php

    $host = 'd124546.mysql.zonevs.eu';
    $db   = 'd124546_bookstore';
    $user = 'd124546_bookstore';
    $pass = 'bv4NFLhbhrxPM3H';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);