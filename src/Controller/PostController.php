<?php


    namespace App\Controller;

    use App\Lib\RequiermentsApp;
    use App\Modele\Modele;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Twig\Environment;

    class PostController extends AbstractController
    {
        /**
         * @var Environment
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }

        public function newPost(){
            $durableSession = new Session();
            $durableSession->start();
            if(!empty($durableSession->get('id'))){
                $modele = new Modele();
                $getUserCars = $modele->getUserCars($durableSession->get('id'));
                return new Response($this->page->render('Post/newPost.html.twig', array('userCars'=>$getUserCars)));
            }else{
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }

        }

        public function validatedNewPost(){

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
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }
        }

    }
