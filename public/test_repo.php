<?php
require '../vendor/autoload.php';

$r = new ReflectionClass('App\Repository\ArticleRepository');
echo $r->getFileName() . "<br>";
echo method_exists($r->newInstanceWithoutConstructor(), 'search') 
    ? '<b style="color:green">search OK ✅</b>' 
    : '<b style="color:red">search ABSENTE ❌</b>';