<?php


    namespace App\Controller;

    use App\Lib\RequiermentsApp;
    use App\Modele\Modele;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Twig\Environment;

    class RegisterController extends AbstractController
    {
        /**
         * @var Environment
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }
        public function signUp()
        {
            // Formulaire à l'aide de Symfony Forms 
            $formRegister = $this->createFormBuilder(array('allow_extra_fields' =>true))
            ->setAction('/register')
            ->setMethod('POST')
            ->add('name', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Nom', 'pattern'=> "^[a-zA-Z -éèî]+$")))
            ->add('surname', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Prénom', 'pattern'=> "^[a-zA-Z -éèî]+$")))
            ->add('mail', EmailType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Email')))
            ->add('phone', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Tél', 'pattern'=> "^[0-9]{10}$")))
            ->add('address', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Adresse', 'pattern'=> "^[a-zA-Z0-9 -éèî]+$")))
            ->add('cp', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Code postal', 'pattern'=> "^[0-9]{5}$")))
            ->add('city', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Ville', 'pattern'=> "^[a-zA-Z0-9 -éèî]+$")))
            ->add('password', PasswordType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Mot de passe')))
            ->add('valider', SubmitType::class, array('label' => 'Je crée mon compte !','attr' => array('class' => 'form_submit_button button')))
            ->getForm();

            // On récupere les infos saisis par l'utilisateur
            $request = Request::createFromGlobals();
            $formRegister->handleRequest($request);

            // On vérifie si le formulaire a été envoyé et valide
        
            if($formRegister->isSubmitted() && $formRegister->isValid()){

                $userData = $formRegister->getData();      //récupére les données saisies par l'user
                $req = new RequiermentsApp();
                if($req->checkPasswordSize($userData['password']) == True){
                    // Si le mot de passe est bon on continue l'ajout du new user
                    $modele = new Modele();
                    $queryResult = $modele->addUser($userData);
                    if($queryResult == 1){     // Je teste si je suis bien co sur la bdd
                        return $this->redirect('/login');
                    }
                    return new Response($this->page->render('Register/register.html.twig', array('formRegister'=>$formRegister->createView())));
                }
                else{
                    
                    // sinon on l'informe que son mot de passe n'est pas bon
                    $warning_pwd = True;
                    return new Response($this->page->render('Register/register.html.twig', array('formRegister'=>$formRegister->createView(), 'warning_pwd'=> $warning_pwd)));
                }

            }
            // On redirige l'user vers la page register pour la première fois lorsqu'il souhaite s'incrire
            return new Response($this->page->render('Register/register.html.twig', array('formRegister'=>$formRegister->createView())));
        }
    }