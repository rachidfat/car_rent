<?php


    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Twig\Environment;

    class LandingController extends AbstractController
    {
        /**
         * @var Environment
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;
        }
        public function landingPage()
        {
            return new Response($this->page->render('Landing/landing.html.twig'));
        }

        public function deconnectionUser()
        {
            $durableSession = new Session();
            $durableSession->start();
            $durableSession->clear();

            return new Response($this->page->render('Landing/landing.html.twig'));
        }
        
        public function error404Page()
        {
            return new Response($this->page->render('Errors\error404.html.twig'));
        }
    }