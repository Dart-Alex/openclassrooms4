<?php
/**
 * Classe représentant une ligne du tableau reports de la bdd
 * 
 * @var int TYPE_OTHER : Code d'un signalement "Autre"
 * @var int TYPE_SPAM : Code d'un signalement "Spam"
 * @var int TYPE_INSULT : Code d'un signalement "Insulte"
 * 
 * @var int $id : Identifiant du report
 * @var int $id_comment : Identifiant du commentaire lié au report
 * @var int $id_user : Identifiant de l'utilisateur qui a signalé
 * @var string $ip : Ip depuis laquelle le signalement a été fait
 * @var string $date_report : Date au format DateTime à laquelle a été fait le report
 * @var int $type : Code du type de signalement
 * @var string $ip : Ip de l'utilisateur
 * @var string $content : Contenu du signalement
 * @var Comment $comment : Commentaire lié au signalement
 * @var User $user : Utilisateur lié au signalement
 * 
 * @see DbObject : classe parente
 */
class Report extends DbObject {

    const TYPE_OTHER = 0;
    const TYPE_SPAM = 1;
    const TYPE_INSULT = 2;

    /**
     * Fonction d'encapsulation
     * 
     * @see DbObject->__set(string $name, $value)
     */
    public function __set(string $name, $value) {
        switch($name) {
            case "id":
                $this->_attributes[$name] = (int) $value;
                break;
            case "id_comment":
                $this->_attributes[$name] = (int) $value;
                break;
            case "id_user":
                $this->_attributes[$name] = (int) $value;
                break;
            case "ip":
                if(self::isIp($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Report: $name($value) n'est pas une ip.");
                }
                break;
            case "date_report":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Report: $name($value) n'est pas une date.");
                }
                break;
            case "type":
                $this->_attributes[$name] = (int) $value;
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Report: $name($value) vide.");
                }
                break;
            case "comment":
                if(is_a($value, 'Comment')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Report: $name(".var_export($value).") n'est pas un Comment.");
                }
                break;
            case "user":
                if(is_a($value, 'User')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Report: $name(".var_export($value).") n'est pas un User.");
                }
                break;
            default:
                throw new Exception("Report: $name($value) inconnu.");
                break;
        }
    }

    /**
     * Cette fonction est appelée lorsqu'on appelle $objet->$name pour retourner les attributs de l'objet.
     * Instancie dynamiquement les objets si ils ne le sont pas déjà.
     * 
     * @param string $name : Nom de l'attribut à retourner
     * 
     * @return mixed : Dépend de l'attribut qu'on a demandé
     * 
     * @see DbObject::__get()
     */
    public function __get(string $name) {
        if(!isset($this->$name)) {
            switch($name) {
                case "user":
                    if($this->id_user != 0) {
                        $userManager = new UserManager();
                        $user = $userManager->getUserById($this->id_user);
                    }
                    else {
                        $user = User::default();
                    }
                    $this->$name = $user;
                    break;
                case "comment":
                    $commentManager = new CommentManager();
                    $comment = $commentManager->getCommentById($this->id_comment);
                    $this->$name = $comment;
                    break;
            }
        }
        return parent::__get($name);
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
        $report = new self([
            "id" => 0,
            "id_comment" => 0,
            "id_user" => 0,
            "ip" => $_SERVER["REMOTE_ADDR"],
            "date_report" => self::now(),
            "type" => self::TYPE_OTHER,
            "content" => "Aucun commentaire"
        ]);
        return $report;
    }
}