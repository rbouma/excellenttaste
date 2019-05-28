<?php

//DATABASE INSTELLINGEN
$db = new MysqliDb (Array (
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'root123',
    'db'=> 'taste',
    'port' => 3306,
    'charset' => 'utf8'));

$db->autoReconnect = true;
