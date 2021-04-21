<?php

require_once 'vendor/autoload.php';

use App\Test;

var_dump($_REQUEST['name']);

$work = new Test($_REQUEST['name']);


try {
    $work->workSSutuliyDog();
} catch (Exception $e) {
}

