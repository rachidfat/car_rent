<?php


    namespace App\Modele;

use Exception;
use \PDO;
use PDOException;

class Modele{

        // Une contante en informatique est toujours en MAJ
        private $DRIVER = 'mysql:host=127.0.0.1;dbname=car_rent';
        private $USER = 'root';
        private $PWD = '';
        private $OPTIONS = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );

        // Ici il faut que je crée la connexion vers ma bdd (Ouvrir la connexion)

        public function connectToDb(){
            try{
                return new \PDO($this->DRIVER, $this->USER, $this->PWD, $this->OPTIONS);
            }catch(PDOException $Exception){
                return FALSE;
            }
        }

        public function addUser($userData){
            // J'ouvre ma connexion vers ma bdd
            $connection = $this->connectToDb();
            // Je teste si la connexion est bien ouverte!
            // Si c'est le cas alors
            if($connection != FALSE){
                // Je prépare ma requete
                $queryString = "INSERT INTO users (name, surname, phone, mail, address, cp, city, password) VALUES (:name, :surname, :phone, :mail, :address, :cp, :city, :password)";
                $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $args = array(
                    "name" => $userData['name'],
                    "surname" => $userData['surname'],
                    "phone" => $userData['phone'],
                    "mail" => $userData['mail'],
                    "address" => $userData['address'],
                    "cp" => $userData['cp'],
                    "city" => $userData['city'],
                    "password" => $userData['password']
                );
                $resultSet = $queryPrepared->execute($args);
               
                // J'ai bien add l'user
                if($resultSet){
                    return 1;
                }
                // Pas le cas
                return 0;
            }
            return FALSE;
            
        }

        public function loginUser($login, $pwd){
            // J'ouvre une connexion vers ma base de données...
            $connection = $this->connectToDb();
            // Je vérifie si ma connexion est bonne..
            if($connection != FALSE){
                // Alors
                $queryString = "SELECT * FROM users WHERE mail = :login AND password = :pwd";
                $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
                $queryPrepared->execute(array("login"=>$login, "pwd"=>$pwd));

                $resultSet = $queryPrepared->fetch();
                return $resultSet;
            }

            echo "$===============> ECHEC CONNEXION !";
            return FALSE;

        }

        // TODO: Le reste
    }