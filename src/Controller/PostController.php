<?php


    namespace App\Controller;

    use App\Lib\RequiermentsApp;
    use App\Modele\Modele;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Twig\Environment; // a rajouter dans chaque controlleur

    class PostController extends AbstractController
    {
        /**
         * @var Environment  // pour cela on rajoute ces lignes la afin de dire ''j'ai le droit d'implementer de nouvelles fonctions !!
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }                     // a toujours avoir dans chaque controlleur

        public function newPost(){
            $durableSession = new Session();
            $durableSession->start();
            if(!empty($durableSession->get('id'))){
                $modele = new Modele();
                $getUserCars = $modele->getUserCars($durableSession->get('id'));
                return new Response($this->page->render('Post/newPost.html.twig', array('userCars'=>$getUserCars)));
            }else{
                // une erreur de connexion...
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }

        }

        public function validatedNewPost(){
            // array(4) { ["title"]=> string(28) "Peugot 5008 location voiture" ["pricePerDay"]=> string(2) "30" ["maxKm"]=> string(4) "2500" ["car"]=> string(1) "1" }

            $durableSession = new Session();

            if(!empty($durableSession->get('id'))){
                $descNewPost = $_POST;
                $modele = new Modele();
                $result = $modele->addNewPost($descNewPost);
                if($result == 1){
                    $durableSession->set('newPostAdded', 'OK');
                }else{
                    $durableSession->set('newPostAdded', 'KO');
                }

                return $this->redirect('/newPost/'.strtolower($durableSession->get('name')));             
            }else{
                // une erreur de connexion...
                echo "<h1>401 (Unauthorized Action) Please login !</h1>";
                return new Response();
            }

        }

        public function consultPost($username){
            $durableSession = new Session();
            $durableSession->start();
            if(!empty($durableSession->get('id'))){

                $model = new Modele();
                $consultPost = $model->consultPost($durableSession->get('id'));
                return new Response($this->page->render('Post/consultPost.html.twig', array('getPost'=>$consultPost)));
            }else{
                // une erreur de connexion...
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }

        }

        public function updatePrice($username){
            $durableSession = $this->get('session');
            if(!empty($durableSession->get('id'))){

                $model = new Modele();
                $id_announcement = $_POST['idAnnouncement'];
                $newPrice = $_POST['newPrice'];
                $modifPost = $model->updatePrice($newPrice, $id_announcement);
                $consultPost = $model->consultPost($durableSession->get('id'));
                return new Response($this->page->render('Post/consultPost.html.twig', array('getPost'=>$consultPost)));
            }else{
                // une erreur de connexion...
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }

        }
    }

