<?php
namespace DartAlex;
/**
 * Gère l'affichage du header de la partie frontend
 */
ob_start();
?>
        
<div id="nav" class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <!-- <div class="container"> -->
        <div class="navbar-brand">
                <a class="navbar-item" href="/"><span class="icon"><i class="fas fa-home"></i></span>&nbsp;Accueil</a>
                <a role="button" aria-label="menu" aria-expanded="false" data-target="navbarMenu" class="navbar-burger burger">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                </a>
        </div>
        <div class="navbar-menu" id="navbarMenu">
                <div class="navbar-start">
                        <?= $menu ?>
                </div>
                <div class="navbar-end">
        
        
<?php
if($_SESSION['user']->id != 0) { // Si l'utilisateur est authentifié, on affiche un lien vers l'édition de son profil, et pour se déconnecter
?>
                        <a class="navbar-item" href="/profile/edit/"><span class="icon"><i class="fas fa-user"></i></span>&nbsp;<?= htmlspecialchars($_SESSION['user']->name) ?></a>
                        <a title="Déconnexion" class="navbar-item" href="/logout/"><span class="icon has-text-danger"><i class="fas fa-times"></span></i>&nbsp;Déconnexion</a>
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
                        <div class="navbar-item has-dropdown">
                                <a class="navbar-link">Connexion</a>
                                <div class="navbar-dropdown is-boxed is-right">
                                        <div class="navbar-item">
                                                <form method="post" action="<?= PATH ?>">
                                                        <input type="hidden" name="action" value="login"/>
                                                        <div class="field">
                                                                <div class="control has-icons-left">
                                                                        <input class="input" type="text" name="name" placeholder="Pseudonyme" required/>
                                                                        <div class="icon is-small is-left">
                                                                                <i class="fas fa-user"></i>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <div class="field has-addons">
                                                                <div class="control has-icons-left is-expanded">
                                                                        <input class="input" type="password" name="password" placeholder="Password" required/>
                                                                        <div class="icon is-small is-left">
                                                                                <i class="fas fa-lock"></i>
                                                                        </div>
                                                                </div>
                                                                <div class="control">
                                                                        <input class="button is-primary" type="submit" value="Ok" id="login-submit"/>
                                                                </div>
                                                        </div>
                                                        
                                                        
                                                </form>
                                        </div>
                                        <hr class="navbar-divider">
                                        <a class="navbar-item" href="/recover/">Mot de passe oublié</a>
                                </div>
                        </div>
                        
                        <a class="navbar-item" href="/register/">S'inscrire</a>
                        
<?php
}
?>
                </div>
        </div>
<!-- </div> -->
</div>

<?php


$header = ob_get_clean();