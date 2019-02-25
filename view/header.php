<?php
/**
 * Gère l'affichage du header de la partie frontend
 */
ob_start();
?>
<header>
        
<div id="nav">
        <?= $menu ?>
<?php
if($_SESSION['user']->id != 0) { // Si l'utilisateur est authentifié, on affiche un lien vers l'édition de son profil, et pour se déconnecter
?>
        <a href="/profile/edit/"><?= $_SESSION['user']->name ?></a>
        <a href="/logout/">Se déconnecter</a>
<?php
}
else { // Si l'utilisateur est anonyme, on affiche le formulaire de connexion, un lien pour s'inscrire et un lien en cas d'oubli du mot de passe
/**
 * Le formulaire renvoie en post vers le chemin actuel les valeurs suivantes :
 * @var string action : login
 * @var string name : Le nom de l'utilisateur (required)
 * @var string password : Le mot de passe de l'utilisateur (required)
 */
?>
        <form method="post" action="<?= PATH ?>">
            <input type="hidden" name="action" value="login" required/>
            <input type="text" name="name" placeholder="Pseudonyme" required/>
            <input type="password" name="password" placeholder="Mot de passe" required/>
            <input type="submit"/>
        </form>
        <a href="/register/">S'inscrire</a>
        <a href="/recover/">Mot de passe oublié</a>
<?php
}
?>
</div>
<h1> Le blog </h1>
</header>

<?php


$header = ob_get_clean();