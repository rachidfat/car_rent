<?php

    namespace App\Lib;
    class RequiermentsApp{

        public function __construct()
        {   
        }

        public function checkPasswordSize($pwd)    // verifie si le mot de passe a entre 12 et 32 caractéres
        {
            if((strlen($pwd) > 11) && (strlen($pwd) < 33)){
                return True;
            }
            return False;
        }

        public function dateFormat($dateTime, $culture){    // retourne la date au format FR
            if(mb_strtoupper($culture) == "FR"){

                list($date, $time) = explode(" ", $dateTime) ;
                list($year, $month, $day) = explode("-", $date);
                $date = "Le ".$day."/".$month."/".$year." à ".$time;

                return $date;
            }
            return $dateTime;
        }

        public function dateDiff($startDate,$endDate){    // retourne la difference entre 2 dates

            $endDateTime = strtotime($endDate);
            $startDateTime = strtotime($startDate);

            $dateDiff = $endDateTime - $startDateTime;

            $days = round($dateDiff / (60 * 60 * 24));
            if($days == 0){
                $days = 1;
            }
            return $days;
        }

        public function applyPrice($startDate,$endDate, $pricePerDay){    // calcul le prix total (nbre de jours x prix par jour)

            $days = $this->dateDiff($startDate,$endDate);
            
            return $pricePerDay * $days;
        }
    }
