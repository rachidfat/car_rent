<?php


    namespace App\Controller;

    use App\Modele\Modele;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Twig\Environment;

    class ProfilController extends AbstractController
    {
        /**
         * @var Environment
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }

        public function redirectToProfilPage($username){
            $durableSession = new Session();
            $durableSession->start();
            if(!empty($durableSession->get('id'))){
                return new Response($this->page->render('Profil/profil.html.twig'));
            }else{
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }

        }
        public function searchPage()
        {
            
            $durableSession = new Session();
            if(!empty($durableSession->get('id'))){
                $durableSession->set('bookValid', '');
                if(!empty($_GET)){
                    $city = $_GET['city'];
                    $startDate = $_GET['startDate'];
                    $endDate = $_GET['endDate'];
                    $Km = $_GET['Km'];

                    $modele = new Modele();
                    $posts = $modele->getPosts($city, $startDate, $endDate, $Km, 5);
                    $session = new Session();
                    if(empty($posts)){
                        $session->set("noResultFound", 'OK');
                        return $this->redirect('/profil/'.strtolower($durableSession->get('name')));
                    }
                    $session->set("startDate", $startDate);
                    $session->set("endDate", $endDate);
                    $session->start();
                    return new Response($this->page->render('Result/resultSearch.html.twig', array("city"=>$city, "result"=>$posts)));
                }
            }else{
                
                if(!empty($_GET)){
                    $city = $_GET['city'];
                    $startDate = $_GET['startDate'];
                    $endDate = $_GET['endDate'];
                    $Km = $_GET['Km'];
                    
                    $modele = new Modele();
                    $posts = $modele->getPosts($city, $startDate, $endDate, $Km, 0);
    
                    $session = new Session();
                    $session->set("startDate", $startDate);
                    $session->set("endDate", $endDate);
                    $session->start();
                    return new Response($this->page->render('Result/resultSearch.html.twig', array("city"=>$city, "result"=>$posts)));
                }
            }
        }
    }