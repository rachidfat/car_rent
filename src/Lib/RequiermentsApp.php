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
    }
