<?php
session_start();

function loadClass($name) {
    require "model/$name.php";
}
spl_autoload_register("loadClass");

if(!isset($_SESSION["user"])) {
    $_SESSION["user"] = User::default();
}