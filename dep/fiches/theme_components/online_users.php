<?php
if(!isset($_SESSION)) session_start();
$path = (isset($_GET["path"]))?$_GET["path"]:"./";
require_once $path.'api/Fonctions.php';
require_once $path.'api/essentiel.php';
require_once $path.'theme_components/theme_style.php';

?>