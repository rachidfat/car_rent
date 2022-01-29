<?php


    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Twig\Environment; // a rajouter dans chaque controlleur

    class LandingController extends AbstractController
    {
        /**
         * @var Environment  // pour cela on rajoute ces lignes la afin de dire ''j'ai le droit d'implementer de nouvelles fonctions !!
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }                     // a toujours avoir dans chaque controlleur
        public function landingPage()
        {

            return new Response($this->page->render('Landing/landing.html.twig'));
            // return new Response("Hello WORLD !");
        }


        public function deconnectionUser()
        {
            // Je ferme la session
            // $id = $this->get('session')->get('id');
            // echo
            $durableSession = new Session();
            $durableSession->start();
            $durableSession->clear();

            return new Response($this->page->render('Landing/landing.html.twig'));
        }
    }