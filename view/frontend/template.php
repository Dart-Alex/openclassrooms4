<?php 
/**
 * Template d'affichage de la page.
 * On doit lui fournir $title pour le titre de la page, et $content pour son contenu.
 */
require('header.php');
require('footer.php'); 
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex,nofollow"/>
		<meta name="author" content="Alexandre Dartinet"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <link href="/public/css/style.css" rel="stylesheet" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="mobile-web-app-capable" content="yes">
        <title><?= $title ?></title>
    </head>
        
    <body>
        <?= $header ?>
        <?= $content ?>
        <?= $footer ?>
    </body>
</html>