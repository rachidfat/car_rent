<?php


    namespace App\Controller;

use App\Lib\RequiermentsApp;
use App\Modele\Modele;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Twig\Environment; // a rajouter dans chaque controlleur

    class LoginController extends AbstractController
    {
        /**
         * @var Environment  // pour cela on rajoute ces lignes la afin de dire ''j'ai le droit d'implementer de nouvelles fonctions !!
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }                     // a toujours avoir dans chaque controlleur
        public function login()
        {

            $formLogin = $this->createFormBuilder(array('allow_extra_fields' =>true))
            ->setAction('/login')
            ->setMethod('POST')
            ->add('login',TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Email')))
            ->add('pwd',PasswordType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Mot de passe')))
            ->add('valider', SubmitType::class, array('label' => 'Je me connecte','attr' => array('class' => 'form_submit_button button')))
            ->getForm();

            $request = Request::createFromGlobals();
            $formLogin->handleRequest($request);

            if($formLogin->isSubmitted() && $formLogin->isValid()){

                // Si l'user a bien saisi les valeurs et que le formulaire a été envoyé avec succès !!
                $credentials = $formLogin->getData();

                $modele = new Modele();
                $user = $modele->loginUser($credentials['login'], $credentials['pwd']);
                if(!empty($user)){

                    $helper = new RequiermentsApp();
                    // echo "Connexion avec succes";
                    $durableSession = new Session();
                    $durableSession->set('id', $user['id']);
                    $durableSession->set('name', $user['name']);
                    $durableSession->set('surname', $user['surname']);
                    $durableSession->set('phone', $user['phone']);
                    $durableSession->set('mail', $user['mail']);
                    $durableSession->set('address', $user['address']);
                    $durableSession->set('city', $user['city']);
                    $durableSession->set('cp', $user['cp']);
                    $durableSession->set('pwd', $user['password']);
                    $durableSession->set('last_connection', $helper->dateFormat($user['last_connection'], "FR"));

                    // return new Response($this->page->render('Profil/profil.html.twig'));
                    return $this->redirect('/profil/'.strtolower($durableSession->get('name')));
                }
                else{
                    return new Response($this->page->render('Login/login.html.twig', array("formLogin" => $formLogin->createView(), "connection_error"=>TRUE)));
                }
                
            }
            return new Response($this->page->render('Login/login.html.twig', array("formLogin" => $formLogin->createView())));
        }
    }