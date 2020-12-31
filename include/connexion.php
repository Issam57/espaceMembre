<?php


$bdd = new PDO('mysql:host=localhost;dbname=membres',"issam","26121985");

$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);