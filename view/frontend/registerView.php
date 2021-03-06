<?php
namespace DartAlex;
/**
 * Gère l'affichage du formulaire d'inscription
 */
ob_start();
?>
<h2 class="title is-3">Formulaire d'inscription</h2>
<?php
/**
 * Le formulaire envoie à / en post les valeurs suivantes :
 * @var string action : registerUser (hidden)
 * @var string name : Nom de l'utilisateur (required)
 * @var string password : Mot de passe (required)
 * @var string password_confirm : Confirmation du mot de passe (required)
 * @var string name_display : Nom d'affichage (required)
 * @var string email : Email (required)
 * @var string email_confirm : Confirmation de l'email (required)
 */
?>
<div class="box container"><form method="post" action="/" id="register-form">
    <input type="hidden" name="action" value="registerUser"/>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="name" class="label">Pseudonyme</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control has-icons-left">
                    <input class="input" type="text" name="name" id="name" placeholder="Entrez votre pseudo ici." required/>
                    <div class="icon is-small is-left">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="password" class="label">Mot de passe</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control is-expanded has-icons-left">
                    <input class="input" type="password" name="password" id="password" placeholder="Mot de passe" required/>
                    <div class="icon is-left is-small">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
            </div>
            <div class="field">
                <div class="control is-expanded has-icons-left">
                    <input class="input" type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe" required/>
                    <div class="icon is-left is-small">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="name_display" class="label">Nom affiché</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control has-icons-left">
                    <input class="input" type="text" name="name_display" id="name_display" placeholder="Entrez le nom que vous voulez afficher." required/>
                    <div class="icon is-left is-small">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label" for="email">Email</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control is-expanded has-icons-left">
                    <input class="input" type="email" name="email" id="email" placeholder="Entrez votre email." required/>
                    <div class="icon is-left is-small">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>
            <div class="field">
                <div class="control is-expanded has-icons-left">
                    <input class="input" type="email" name="email_confirm" id="email_confirm" placeholder="Confirmez votre email." required/>
                    <div class="icon is-left is-small">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label class="label"></label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <label class="checkbox">
                        <input type="checkbox" required/>
                        J'accepte la <a href="/polconf/" target="_blank">politique de confidentialité</a>.
                    </label>
                </div>
            </div>
        </div>
        
    </div>
    <div class="field is-grouped is-grouped-centered">
        <input class="button is-primary" type="submit" value="S'inscrire"/>
    </div>
    
</form></div>


<?php
$scripts = [
    '<script src="/public/js/passwordVerify.js"></script>',
    '<script src="/public/js/emailVerify.js"></script>'
];
$content = ob_get_clean();
require('template.php');