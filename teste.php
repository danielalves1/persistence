<?php
require "./bin/Persistence.php";
require "./bin/Pojo.php";
$core = new Persistence();


$object = new Empresa();
$object->razao = 'ped';

echo $core->mountSelect($object);

?>