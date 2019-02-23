<?php

class Comment extends DbObject {


    public function __set($name, $value) {
        switch($name) {
            case "id":
                $this->_attributes[$name] = (int) $value;
                break;
            case "id_post":
                $this->_attributes[$name] = (int) $value;
                break;
            case "id_user":
                $this->_attributes[$name] = (int) $value;
                break;
            case "reply_to":
                $this->_attributes[$name] = (int) $value;
                break;
            case "date_publication":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) n'est pas une date.");
                }
                break;
            case "ip":
                if(self::isIp($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) n'est pas une ip.");
                }
                break;
            case "name":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) est vide.");
                }
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) est vide.");
                }
                break;
            case "replies_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            default:
                throw new Exception("Comment: $name($value) attribut inconnu.");
                break;
        }
    }

    public static function default() {
        $comment = new self([
            "id" => 0,
            "id_post" => 0,
            "id_user" => 0,
            "reply_to" => 0,
            "date_publication" => self::now(),
            "ip" => $_SERVER['REMOTE_ADDR'],
            "name" => "Anonyme",
            "content" => "nothing",
            "replies_nbr" => 0
        ]);
        return $comment;
    }

    public function getName() {
        if($this->id_user == 0) {
            return $this->name;
        }
        else {
            $userManager = new UserManager();
            $user = $userManager->getUserById($this->id_user);
            return $user->name_display;
        }
    }

}