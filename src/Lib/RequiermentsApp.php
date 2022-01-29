<?php

    namespace App\Lib;
    class RequiermentsApp{

        public function __construct()
        {   
        }

        /**
         * Cette fonction retourne True si la taille du mot de passe est entre 12 et 32 caractéres
         * False le cas échéant !
         */
        public function checkPasswordSize($pwd)
        {
            if((strlen($pwd) > 11) && (strlen($pwd) < 33)){
                return True;
            }
            return False;
        }

        public function dateFormat($dateTime, $culture){
            if(mb_strtoupper($culture) == "FR"){

                list($date, $time) = explode(" ", $dateTime) ;
                list($year, $month, $day) = explode("-", $date);
                $date = "Le ".$day."/".$month."/".$year." à ".$time;

                return $date;
            }
            //else si la culture c'est EN
            // TODO:
            
            return $dateTime;

        }

        public function dateDiff($startDate,$endDate){

            // Here to calculate DateDiffe
            $endDateTime = strtotime($endDate); // or your date as well
            $startDateTime = strtotime($startDate);

            $dateDiff = $endDateTime - $startDateTime;

            $days = round($dateDiff / (60 * 60 * 24));
            if($days == 0){
                $days = 1;
            }
            return $days;
        }

        public function applyPrice($startDate,$endDate, $pricePerDay){

            $days = $this->dateDiff($startDate,$endDate);
            
            return $pricePerDay * $days;
        }
    }
