<?php
namespace DartAlex;
/**
 * Routeur de toutes les requêtes sur le site qui ne vont pas vers /public
 */
require('init.php'); // On inclut le fichier d'initialisation du site

try { // Gestion des erreurs
    require('controller/common.php'); // Controlleur commun à toutes les sections
    /**
     * Bloc de la section backend
     */
    if(preg_match('/^\/admin\//', PATH)) {
        if($_SESSION['user']->level >= User::LEVEL_MODERATOR) { // L'utilisateur doit au moins être modérateur pour accéder à cette section
            require('controller/backend.php');
            if(isset($_POST['action'])) {
                switch($_POST['action']) {
                    case "newPost":
                        if(isset($_POST['title']) && isset($_POST['date_publication']) && isset($_POST['content'])) {
                            $published = isset($_POST['published']);
                            newPost($published, $_POST['title'], Post::htmlToDate($_POST['date_publication']), $_POST['content']);
                        }
                        else {
                            throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                            header('Location: '.PATH.'retry/missing_fields/');
                        }
                        break;
                    case "modifyPost":
                        if(isset($_POST['id_post']) && isset($_POST['id_user']) && isset($_POST['title']) && isset($_POST['date_publication']) && isset($_POST['content'])) {
                            $published = isset($_POST['published']);
                            modifyPost((int) $_POST['id_post'], (int) $_POST['id_user'], $published, $_POST['title'], Post::htmlToDate($_POST['date_publication']), $_POST['content']);
                        }
                        else {
                            throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                            header('Location: '.PATH.'retry/missing_fields/');
                        }
                        break;
                    case "modifyUserLevel":
                        if(isset($_POST['id']) && isset($_POST['level'])) {
                            modifyUserLevel((int) $_POST['id'], (int) $_POST['level']); 
                        }
                        else {
                            throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                            header('Location: '.PATH.'retry/missing_fields/');
                        }
                        break;
                    case "addBan":
                        if(isset($_POST['ip']) && isset($_POST['type']) && isset($_POST['content'])) {
                            addBan($_POST['ip'], (int) $_POST['type'], $_POST['content']);
                        }
                        else {
                            throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                            header('Location: '.PATH.'retry/missing_fields/');
                        }
                        break;
                    case "modifyBan":
                        if(isset($_POST['id']) && isset($_POST['ip']) && isset($_POST['type']) && isset($_POST['content'])) {
                            modifyBan((int) $_POST['id'], $_POST['ip'], (int) $_POST['type'], $_POST['content']);
                        }
                        else {
                            throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                            header('Location: '.PATH.'retry/missing_fields/');
                        }
                        break;
                    case "addImage":
                        if(isset($_POST['title']) && isset($_FILES['file'])) {
                            addImage($_POST['title'], $_FILES['file']);
                        }
                        else {
                            throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                            header('Location: '.PATH.'retry/missing_fields/');
                        }
                        break;
                    default:
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: l\'action n\'existe pas.');
                        header('Location: '.PATH.'retry/unknown_action/');
                        break;
                }
            }
            elseif(preg_match('/^\/admin\/reports\//', PATH)) { // Section signalement
                $page = getPage(PATH);
                if(preg_match('/\/delete\/\d+\//', PATH)) { // Si one essaye de supprimer un commentaire
                    $id = (int) preg_replace('/^.*\/delete\/(\d+)\/.*$/', '$1', PATH);
                    deleteComment($id);
                }
                elseif(preg_match('/\/comment\/\d+\//', PATH)) { // Signalements d'un commentaire
                    if(preg_match('/\/delete_report\/\d+\//', PATH)) { // Si on supprime un signalement
                        $id = (int) preg_replace('/^.*\/delete_report\/(\d+)\/.*$/', '$1', PATH);
                        deleteReport($id);
                    }
                    else { // On affiche les signalement du commentaire
                        $id = (int) preg_replace('/^.*\/comment\/(\d+)\/.*$/', '$1', PATH);
                        viewCommentReports($id, $page);
                    }
                }
                else { // On affiche les commentaires signalés
                    listReports($page);
                }
            }
            elseif(preg_match('/^\/admin\/posts\//', PATH) && $_SESSION['user']->level >= User::LEVEL_EDITOR) { // Section articles
                if(preg_match('/\/unpublish\/\d+\//', PATH)) {
                    $id = (int) preg_replace('/^.*\/unpublish\/(\d+)\/.*$/', '$1', PATH);
                    publishPost($id, false);
                }
                elseif(preg_match('/\/publish\/\d+\//', PATH)) {
                    $id = (int) preg_replace('/^.*\/publish\/(\d+)\/.*$/', '$1', PATH);
                    publishPost($id, true);
                }
                elseif(preg_match('/\/new\//', PATH)) {
                    viewPost(0);
                }
                elseif(preg_match('/\/edit\/\d+\//', PATH)) {
                    $id = (int) preg_replace('/^.*\/edit\/(\d+)\/.*$/', '$1', PATH);
                    viewPost($id);
                }
                elseif(preg_match('/\/delete\/\d+\//', PATH)) {
                    $id = (int) preg_replace('/^.*\/delete\/(\d+)\/.*$/', '$1', PATH);
                    deletePost($id);
                }
                else {
                    $page = getPage(PATH);
                    listPosts($page);
                }
            }
            elseif(preg_match('/^\/admin\/users\//', PATH) && $_SESSION['user']->level >= User::LEVEL_ADMIN) { // Section utilisateurs
                if(preg_match('/\/delete\/\d+\//', PATH)) {
                    $id = (int) preg_replace('/^.*\/delete\/(\d+)\/.*$/', '$1', PATH);
                    deleteUser($id);
                }
                else {
                    $page = getPage(PATH);
                    listUsers($page);
                }
            }
            elseif(preg_match('/^\/admin\/bans\//', PATH) && $_SESSION['user']->level >= User::LEVEL_ADMIN) { // Section bannissements
                if(preg_match('/\/delete\/\d+\//', PATH)) {
                    $id = (int) preg_replace('/^.*\/delete\/(\d+)\/.*$/', '$1', PATH);
                    deleteBan($id);
                }
                else {
                    $page = getPage(PATH);
                    listBans($page);
                }
            }
            elseif(preg_match('/^\/admin\/images\//', PATH) && $_SESSION['user']->level >= User::LEVEL_EDITOR) {
                if(preg_match('/\/delete\/\d+\//', PATH)) {
                    $id = (int) preg_replace('/^.*\/delete\/(\d+)\/.*$/', '$1', PATH);
                    deleteImage($id);
                }
                else {
                    $page = getPage(PATH);
                    listImages($page);
                }
            }
            else { // Page d'accueil de l'interface d'administration
                viewAdmin();
            }
        }
        else { // On n'a pas le droit de voir cette page
            header('Location: /retry/no_access/');
        }
    }
    /**
     * Bloc pour le contenu généré dynamiquement
     */
    elseif(preg_match('/^\/generated\//', PATH)) {
        require('controller/generated.php');
        if(preg_match('/\/image\/\w+\.png/', PATH)) { // Images générées
            $filename = preg_replace('/^.*\/image\/(\w+\.png).*$/', '$1', PATH);
            displayImage($filename);
        }
        elseif(preg_match('/\/json\//', PATH)) {
            if(preg_match('/images\.json/', PATH)) {
                displayImagesJson();
            }
            else {
                displayErrorJson("no_access");
            }
        }
        elseif(preg_match('/\/js\//', PATH)) {
            if(preg_match('/init\.js/', PATH)) {
                $path = preg_replace('/^.*init\.js;(\/.*\/)$/', '$1', PATH);
                displayInitJs($path);
            }
        }
        else {
            header('Location: /retry/no_access/');
        }
    }
    /**
     * Bloc des requêtes ajax
     */
    elseif(preg_match('/^\/ajax\//', PATH)) {
        require('controller/ajax.php');
        if(preg_match('/^\/ajax\/reports\//', PATH)) {
            if(preg_match('/\/send\//', PATH)) {
                if(isset($_POST['id_comment']) && isset($_POST['type']) && isset($_POST['content'])) {
                    sendReport((int) $_POST['id_comment'], (int) $_POST['type'], $_POST['content']);
                }
                else {
                    displayErrorJson('missing_fields');
                }
            }
            else {
                displayErrorJson('no_access');
            }
        }
        elseif(preg_match('/^\/ajax\/comments\//', PATH)) {
            if(preg_match('/\/get\/\d+\//', PATH)) {
                $id = (int) preg_replace('/^.*\/get\/(\d+)\/.*$/', '$1', PATH);
                displayCommentsJson($id);
            }
            elseif(preg_match('/\/update\/\d+\/\d+\//', PATH)) {
                $id_post = (int) preg_replace('/^.*\/update\/(\d+)\/\d+\/.*$/', '$1', PATH);
                $last_id = (int) preg_replace('/^.*\/update\/\d+\/(\d+)\/.*$/', '$1', PATH);
                updateCommentsJson($id_post, $last_id);
            }
            elseif(preg_match('/\/send\//', PATH)) {
                if(isset($_POST['content']) && isset($_POST['id_post']) && isset($_POST['name']) && isset($_POST['reply_to'])) {
                    sendComment((int) $_POST['id_post'], (int) $_POST['reply_to'], $_POST['name'], $_POST['content']);
                }
                else {
                    displayErrorJson("missing_fields");
                }
            }
            elseif(preg_match('/\/modify\//', PATH)) {
                if(isset($_POST['content']) && isset($_POST['id']) && isset($_POST['name'])) {
                    modifyComment((int) $_POST['id'], $_POST["name"], $_POST['content']);
                }
                else {
                    displayErrorJson("missing_fields");
                }
            }
            elseif(preg_match('/\/delete\//', PATH)) {
                if(isset($_POST['id'])) {
                    deleteComment((int) $_POST['id']);
                }
                else {
                    displayErrorJson("missing_fields");
                }
            }
            else {
                displayErrorJson("no_access");
            }
        }
    }
    /**
     * Bloc de la section frontend
     */
    else {
        require('controller/frontend.php'); // On inclut le contrôleur frontend
        /**
         * Gestion des différentes actions envoyées en post à la partie frontend
         */
        if(isset($_POST['action'])) {
            switch($_POST['action']) {
                case "commentPost":
                    if(isset($_POST['id_post']) && isset($_POST['name']) && isset($_POST['content'])) {
                        commentPost((int) $_POST['id_post'], (string) $_POST['name'], (string) $_POST['content'], (int) $_POST['reply_to']);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                    break;
                case "modifyComment":
                    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['content'])) {
                        modifyComment((int) $_POST['id'], $_POST['name'], $_POST['content']);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                case "login":
                    if(isset($_POST['name']) && isset($_POST['password'])) {
                        login($_POST['name'], $_POST['password'], PATH);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                    break;
                case "registerUser":
                    if(isset($_POST['name']) && isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['email']) && isset($_POST['email_confirm']) && isset($_POST['name_display'])) {
                        registerUser($_POST['name'], $_POST['password'], $_POST['password_confirm'], $_POST['email'], $_POST['email_confirm'], $_POST['name_display']);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                    break;
                case "modifyUser":
                    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['email_confirm']) && isset($_POST['name_display'])) {
                        if(isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['old_password'])) { // Si le mot de passe est modifié
                            $password = $_POST['password'];
                            $password_confirm = $_POST['password_confirm'];
                            $old_password = $_POST['old_password'];
                        }
                        else { // Sinon on envoie du rien
                            $password = '';
                            $old_password = '';
                        }
                        modifyUser((int) $_POST['id'], $_POST['name'], $_POST['name_display'], $_POST['email'], $_POST['email_confirm'], $password, $password_confirm, $old_password);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                    break;
                case "sendContactForm":
                    if(isset($_POST['email']) && isset($_POST['email_confirm']) && isset($_POST['name']) && isset($_POST['message'])) {
                        sendContactForm($_POST['email'], $_POST['email_confirm'], $_POST['name'], $_POST['message']);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                case "sendRecover":
                    if(isset($_POST['recover'])) {
                        sendRecover($_POST['recover']);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                    break;
                case "useRecover":
                    if(isset($_POST['key']) && isset($_POST['id_user']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
                        useRecover($_POST['key'], (int) $_POST['id_user'], $_POST['password'], $_POST['password_confirm']);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                    break;
                case "sendReport":
                    if(isset($_POST['id_comment']) && isset($_POST['type']) && isset($_POST['content'])) {
                        sendReport((int) $_POST['id_comment'], (int) $_POST['type'], $_POST['content']);
                    }
                    else {
                        throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                        header('Location: '.PATH.'retry/missing_fields/');
                    }
                    break;
                default:
                    throw new \Exception('$_POST["action"]('.$_POST['action'].') erreur: l\'action n\'existe pas.');
                    header('Location: '.PATH.'retry/unknown_action/');
                    break;
            }
        }
        else {
            /**
             * Routage vers les différentes pages en fonction de l'addresse fournie en utilisant le controlleur frontend
             */
            if (preg_match('/^\/logout\//', PATH)) {
                logout();
            }
            elseif (preg_match('/^\/recover\/(retry\/\w+\/)?$/', PATH)) {
                viewRecoverPassword();
            }
            elseif (preg_match('/^\/recover\/.+\/(retry\/\w+\/)?$/', PATH)) {
                /**
                 * On passe la clé fournie dans la barre d'adresse au controlleur
                 */
                $key = preg_replace('/^\/recover\/(.+)\/(retry\/\w+\/)?$/', '$1', PATH); 
                viewRecoverPasswordLink($key);
            }
            elseif (preg_match('/^\/post\/\d+\//', PATH)) {
                if(preg_match('/delete\/\d+\//', PATH)) { // Si on a demandé la suppression d'un commentaire
                    $id_comment = (int) preg_replace('/^.*delete\/(\d+)\/.*$/', '$1', PATH);
                    deleteComment($id_comment);
                }
                elseif(preg_match('/report\/\d+\//', PATH)) { // Si on signale un commentaire
                    $id_comment = (int) preg_replace('/^.*report\/(\d+)\/.*$/', '$1', PATH);
                    $path = preg_replace('/report\/\d+\//', '', PATH);
                    viewReportForm($id_comment, $path);
                }
                else {
                    $id_post = (int) preg_replace('/^\/post\/(\d+)\/.*$/', '$1', PATH);
                    $page = getPage(PATH);
                    viewPost($id_post, $page);
                }
            }
            elseif (preg_match('/^\/polconf\//', PATH)) {
                viewPolconf();
            }
            elseif (preg_match('/^\/register\//', PATH)) {
                viewRegister();
            }
            elseif (preg_match('/^\/profile\/edit\//', PATH)) {
                viewProfileEdit();
            }
            elseif (preg_match('/^\/contact\//', PATH)) {
                viewContactForm();
            }
            elseif (preg_match('/^\/directory\//', PATH)) {
                $page = getPage(PATH);
                viewDirectory($page);
            }
            elseif (preg_match('/^\/profile\/\d+\//', PATH)) {
                $id = (int) preg_replace('/^\/profile\/(\d+)\/.*$/', '$1', PATH);
                viewProfile($id);
            }
            elseif (preg_match('/^\/archive\//', PATH)) {
                $year = 0;
                $month = 0;
                $day = 0;
                if(preg_match('/^\/archive\/\d{4}\//', PATH)) {
                    $year = (int) preg_replace('/^\/archive\/(\d{4})\/.*$/', '$1', PATH);
                }
                if(preg_match('/^\/archive\/\d{4}\/\d{2}\//', PATH)) {
                    $month = (int) preg_replace('/^\/archive\/\d{4}\/(\d{2})\/.*$/', '$1', PATH);
                }
                if(preg_match('/^\/archive\/\d{4}\/\d{2}\/\d{2}\//', PATH)) {
                    $day = (int) preg_replace('/^\/archive\/\d{4}\/\d{2}\/(\d{2})\/.*$/', '$1', PATH);
                }
                $page = getPage(PATH);
                viewArchive($page, $year, $month, $day);
            }
            elseif (preg_match('/^\/(page-\d+\/)?$/', PATH)) {
                $page = getPage(PATH);
                listPosts($page);
            }
            else {
                header('Location: /retry/no_access/');
            }
        }
    }
}
catch(\Exception $e) { // Affichage des erreurs
    echo '<div>Erreur : ' . $e->getMessage() . 
        '<br/>File : ' . $e->getFile() . 
        '<br/>Line : ' . $e->getLine() . 
        '<br/>Previous : '. $e->getPrevious() . 
        '<br/>Trace : '. $e->getTraceAsString() . '</div>';
}
