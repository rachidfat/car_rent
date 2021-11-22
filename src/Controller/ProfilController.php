<?php


    namespace App\Controller;
    
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Twig\Environment; // a rajouter dans chaque controlleur

    class ProfilController extends AbstractController
    {
        /**
         * @var Environment  // pour cela on rajoute ces lignes la afin de dire ''j'ai le droit d'implementer de nouvelles fonctions !!
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }                     // a toujours avoir dans chaque controlleur

        public function redirectToProfilPage($username){
            $durableSession = new Session();
            $durableSession->start();
            if(!empty($durableSession->get('id'))){
                return new Response($this->page->render('Profil/profil.html.twig'));
            }else{
                // une erreur de connexion...
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }

        }
        
    }