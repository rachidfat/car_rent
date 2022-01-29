<?php


    namespace App\Controller;

    use App\Modele\Modele;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Twig\Environment; // a rajouter dans chaque controlleur

    class CarsController extends AbstractController
    {
        /**
         * @var Environment  // pour cela on rajoute ces lignes la afin de dire ''j'ai le droit d'implementer de nouvelles fonctions !!
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }                     // a toujours avoir dans chaque controlleur
        public function carsPage()
        {

            $modele = new Modele();
            $durableSession = new Session();

            // Get user authentifiet !!! Get id
            $myCars = $modele->getUserCars($durableSession->get('id'));
            $getCategoryCar = $modele->getCategoryCar();
            $getCarType = $modele->getCarType();
            $getCarEnergy = $modele->getCarEnergy();
            return new Response($this->page->render('Cars/cars.html.twig', array("myCars" => $myCars, "getCategoryCar" => $getCategoryCar, "getCarType"=>$getCarType, "getCarEnergy"=>$getCarEnergy)));
            // return new Response("Hello WORLD !");
        }


        public function addNewcar(){
            
            $modele = new Modele();
            $durableSession = new Session();

            // Get user authentifiet !!! Get id

            $target = getcwd().'\\assets\\images\\';
            $target_file = $target.$durableSession->get('id')."_".$_POST['modele']."_".$_POST['km'].".".strtolower(pathinfo($_FILES["car_pic"]["name"],PATHINFO_EXTENSION));
            
            move_uploaded_file($_FILES["car_pic"]["tmp_name"], $target_file);
            
            $getCategoryCar = $modele->getCategoryCar();
            $getCarType = $modele->getCarType();
            $getCarEnergy = $modele->getCarEnergy();
            $result = $modele->addNewCar($_POST, $durableSession->get('id'));
            $myCars = $modele->getUserCars($durableSession->get('id'));
            if($result == 1){
                return new Response(
                    $this->page->render(
                        'Cars/cars.html.twig', 
                        array( // Params
                            "myCars" => $myCars,
                            "getCategoryCar" => $getCategoryCar,
                            "getCarType"=>$getCarType,
                            "getCarEnergy"=>$getCarEnergy,
                            "inserted" => True
                        )
                    )
                );
            }
            return new Response(
                $this->page->render(
                    'Cars/cars.html.twig', 
                    array( // Params
                        "myCars" => $myCars,
                        "getCategoryCar" => $getCategoryCar,
                        "getCarType"=>$getCarType,
                        "getCarEnergy"=>$getCarEnergy,
                        "inserted" => False
                    )
                )
            );

        }


        public function removeCar(){
            
            $durableSession = $this->get('session');
            if(!empty($durableSession->get('id'))){
                $modele = new Modele();
                $result = $modele->removeCar($_POST['idCar']);
                if($result == 1){
                    $durableSession->set('cars-delited', 'OK');
                }else{
                    $durableSession->set('cars-delited', 'KO');
                }
                return $this->redirect('/mycars/'.strtolower($durableSession->get('name')));
            }

            // une erreur de connexion...
            echo "<h1>401 (Unauthorized action) Please login !</h1>";
            return new Response();
        }

        
    }