<?php


    namespace App\Controller;

    use App\Lib\RequiermentsApp;
    use App\Modele\Modele;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Twig\Environment;

    class BookController extends AbstractController
    {
        /**
         * @var Environment
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }

        public function bookPage($id_announcement){
            $durableSession = new Session();
            $durableSession->start();
            if(!empty($durableSession->get('id'))){
                $modele = new Modele();
                $requirements = new RequiermentsApp();
                $post = $modele->getPost($id_announcement);
                $post['dateDiff'] = $requirements->dateDiff($durableSession->get('startDate'), $durableSession->get('endDate'));
                $post['totalPrice'] = $requirements->applyPrice($durableSession->get('startDate'), $durableSession->get('endDate'), $post['price_per_day']);
                $comments = $modele->getComments($post['id_car']);
                return new Response($this->page->render('Book/book.html.twig', array('post'=>$post, 'comments'=>$comments)));
            }else{
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }
        }

        public function bookValidPage($id_announcement){
            $durableSession = new Session();
            if(!empty($durableSession->get('id'))){
                $modele = new Modele();

                $idUser = $_POST['idUser'];
                $startDate = $_POST['startDate'];
                $endDate = $_POST['endDate'];
                $price = $_POST['price'];

                $bookStatus = $modele->addBooking($id_announcement, $idUser, $startDate, $endDate, $price);
            
                if($bookStatus == 1){
                    $durableSession->set('bookValid', 'OK');
                }else{
                    $durableSession->set('bookValid', 'KO');
                }
                return $this->redirect('/book/'.$id_announcement);
            }else{
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }

        }
  
    }

    