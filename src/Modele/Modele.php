<?php

    namespace App\Modele;

    use Exception;
    use \PDO;
    use PDOException;

class Modele{

    private $DRIVER = 'mysql:host=127.0.0.1;dbname=car_rent';
    private $USER = 'root';
    private $PWD = '';
    private $OPTIONS = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    );

    public function connectToDb(){    // connection vers bdd
        try{
            return new \PDO($this->DRIVER, $this->USER, $this->PWD, $this->OPTIONS);
        }catch(PDOException $Exception){
            return FALSE;
        }
    }

    public function addUser($userData){
        $connection = $this->connectToDb();
        if($connection != FALSE){
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
            
            if($resultSet){    // teste si l'utilisateur a bien ete rajouté
                return 1;
            }
            return 0;
        }
        return FALSE;            
    }

    public function loginUser($login, $pwd){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT * FROM users WHERE mail = :login AND password = :pwd";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
            $queryPrepared->execute(array("login"=>$login, "pwd"=>$pwd));

            $resultSet = $queryPrepared->fetch();
            if(!empty($resultSet)){
                $queryString = "UPDATE users set last_connection = :currentDateTime WHERE mail = :login AND password = :pwd";
                $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
                $args = array("currentDateTime"=>date("Y-m-d h:i:s"), "login"=>$login, "pwd"=>$pwd);
                $queryPrepared->execute($args);
            }
            
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }

    public function getUserCars($idUser){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT c.id as car_id, c.*, cc.*, ct.*, e.*
                            FROM car c INNER JOIN car_type ct
                            ON ct.id = c.id_type
                            INNER JOIN category_car cc
                            ON cc.id = c.id_category
                            INNER JOIN energy e
                            ON e.id = c.id_energy
                            WHERE id_user = :idUser
                            ORDER BY c.counter_km DESC
                            ";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
            $queryPrepared->execute(array("idUser"=>$idUser));

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }
        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }

    public function getPosts($city, $startDate, $endDate, $km, $idUser){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT a.*, c.*, u.name, u.surname, u.city, ct.car_type, cc.category_car, e.energy
            FROM announcement a
            INNER JOIN car c ON a.id_car = c.id
            INNER JOIN car_type ct ON ct.id = c.id_type
            INNER JOIN category_car cc ON cc.id = c.id_category
            INNER JOIN energy e ON e.id = c.id_energy
            INNER JOIN users u ON c.id_user = u.id
            WHERE UPPER(u.city) = :city
            AND a.max_km >= :km
            AND id_announcement NOT IN (
                SELECT id_announcement FROM book WHERE (end_date BETWEEN :startDate AND :endDate)
            )
            AND c.id_user NOT IN (:idUser)
            ORDER BY a.price_per_day DESC
            ";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
            $queryPrepared->execute(array("city"=>strtoupper($city), "km"=>$km, "startDate"=>$startDate, "endDate"=>$endDate, "idUser"=>$idUser));

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }

    public function getPost($id_announcement){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT a.*, c.*, u.name, u.surname, u.city, ct.car_type, cc.category_car, e.energy
            FROM announcement a
            INNER JOIN car c ON a.id_car = c.id
            INNER JOIN car_type ct ON ct.id = c.id_type
            INNER JOIN category_car cc ON cc.id = c.id_category
            INNER JOIN energy e ON e.id = c.id_energy
            INNER JOIN users u ON c.id_user = u.id
            WHERE id_announcement = :id";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
            $queryPrepared->execute(array("id"=>$id_announcement));

            $resultSet = $queryPrepared->fetch();
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }

    public function getComments($idCar){

        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT c.*, u.name, u.surname
            FROM comment c
            INNER JOIN users u
            ON u.id = c.id_user
            where c.id_car = :idCar
            ";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
            $queryPrepared->execute(array("idCar"=>$idCar));

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }

    public function addBooking($idAnnouncement, $idUser, $startDate, $endDate, $price){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            
            $queryString = "INSERT INTO book (id_user, id_announcement, start_date, end_date, price) VALUES (:idUser, :idAnnouncement, :startDate, :endDate, :price)";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $args = array(
                "idAnnouncement" => $idAnnouncement,
                "idUser" => $idUser,
                "startDate" => $startDate,
                "endDate" => $endDate,
                "price" => $price
            );
            $resultSet = $queryPrepared->execute($args);
            
            if($resultSet){    // teste si j'ai bien ajouté la réservation
                return 1;
            }
            return 0;
        }
        return FALSE;
    }

    public function addNewPost($descPost){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            
            $queryString = "INSERT INTO announcement (title, max_km, price_per_day, id_car) VALUES (:title, :max_km, :price_per_day, :id_car)";
            echo "INSERT INTO announcement (title, max_km, price_per_day, id_car) VALUES (".$descPost['title'].", ".$descPost['maxKm'].", ".$descPost['pricePerDay'].", ".$descPost['car'].")";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $args = array(
                "title" => $descPost['title'],
                "max_km" => $descPost['maxKm'],
                "price_per_day" => $descPost['pricePerDay'],
                "id_car" => $descPost['car']
            );
            $resultSet = $queryPrepared->execute($args);
            
            if($resultSet){     // teste si j'ai bien ajouté le post
                return 1;
            }
            return 0;
        }
        return FALSE;
    }

    public function getCategoryCar(){

        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT * FROM category_car";
            $queryPrepared = $connection->prepare($queryString);
            $queryPrepared->execute();

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }

    public function getCarType(){

        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT * FROM car_type";
            $queryPrepared = $connection->prepare($queryString);
            $queryPrepared->execute();

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;

    }

    public function getCarEnergy(){

        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT * FROM energy";
            $queryPrepared = $connection->prepare($queryString);
            $queryPrepared->execute();

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }

        echo "===> ECHEC CONNEXION !";
        return FALSE;
    }

    public function addNewCar($infos, $id_user){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            
            $queryString = "INSERT INTO car (marque,modele,year,descCar,id_category,id_type,id_energy,id_user,seat_number,doors_number,counter_km) VALUES (:marque,:modele,:year,:descCar,:id_category,:id_type,:id_energy,:id_user,:seat_number,:doors_number,:counter_km)";
            
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $args = array(
                "marque" => $infos['marque'],
                "modele" => $infos['modele'],
                "year" => $infos['year'],
                "descCar" => $infos['descCar'],
                "id_category" => $infos['category'],
                "id_type" => $infos['type_'],
                "id_energy" => $infos['energy'],
                "id_user" => $id_user,
                "seat_number" => $infos['seatNumber'],
                "doors_number" => $infos['doorsNumber'],
                "counter_km" => $infos['km']
            );
            $resultSet = $queryPrepared->execute($args);
            if($resultSet){    // teste si j'ai bien ajouté une nouvelle voiture
                return 1;
            }
            return 0;
        }
        return FALSE;
    }

    public function updateUserPwd($userData){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "UPDATE users SET password = :password WHERE id = :userId";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $args = array(
                "password" => $userData['new_pwd'],
                "userId" => $userData['userId']
            );
            $resultSet = $queryPrepared->execute($args);
            
            if($resultSet){ // teste si j'ai bien changer le mdp
                return 1;
            }
            return 0;
        }
        return FALSE;
    }


    public function removeCar($idCar){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "DELETE FROM announcement where id_car=:idCar";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $args = array(
                "idCar" => $idCar
            );
            $resultSet = $queryPrepared->execute($args);

            $queryString = "DELETE FROM car where id=:idCar";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    
            $resultSet = $queryPrepared->execute($args);
            
            if($resultSet){    // teste si j'ai bien retiré la voiture
                return 1;
            }
            return 0;
        }
        return FALSE;
    }

    public function consultPost($id_user){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT a.*, c.*, u.name, u.surname, u.city, ct.car_type, cc.category_car, e.energy
            FROM announcement a
            INNER JOIN car c ON a.id_car = c.id
            INNER JOIN car_type ct ON ct.id = c.id_type
            INNER JOIN category_car cc ON cc.id = c.id_category
            INNER JOIN energy e ON e.id = c.id_energy
            INNER JOIN users u ON c.id_user = u.id
            WHERE c.id_user = :id_user";

            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
            $queryPrepared->execute(array("id_user"=>$id_user));

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }


    public function changePost($id_user){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "SELECT a.*, c.*, u.name, u.surname, u.city, ct.car_type, cc.category_car, e.energy
            FROM announcement a
            INNER JOIN car c ON a.id_car = c.id
            INNER JOIN car_type ct ON ct.id = c.id_type
            INNER JOIN category_car cc ON cc.id = c.id_category
            INNER JOIN energy e ON e.id = c.id_energy
            INNER JOIN users u ON c.id_user = u.id
            WHERE c.id_user = :id_user";

            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR =>PDO::CURSOR_FWDONLY));
            $queryPrepared->execute(array("id_user"=>$id_user));

            $resultSet = $queryPrepared->fetchAll();
            return $resultSet;
        }

        echo "==> ECHEC CONNEXION !";
        return FALSE;
    }

    public function updatePrice($newPrice, $idAnnouncement){
        $connection = $this->connectToDb();
        if($connection != FALSE){
            $queryString = "UPDATE announcement SET price_per_day = :price WHERE id_announcement = :id_announcement";
            $queryPrepared = $connection->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $args = array(
                "price" => $newPrice,
                "id_announcement" => $idAnnouncement
            );
            $resultSet = $queryPrepared->execute($args);
            
            if($resultSet){    // teste si j'ai bien changé le prix
                return 1;
            }
            return 0;
        }
        return FALSE;
        }
    }