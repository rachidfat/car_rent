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
    use Twig\Environment; // a rajouter dans chaque controlleur

    class RegisterController extends AbstractController
    {
        /**
         * @var Environment  // pour cela on rajoute ces lignes la afin de dire ''j'ai le droit d'implementer de nouvelles fonctions !!
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }                     // a toujours avoir dans chaque controlleur
        public function signUp()
        {
            // Ici on prépare notre formulaire (dans le back) et de l'envoyer au front!
            // Tout ceci est fait à l'aide de Symfony Forms 
            $formRegister = $this->createFormBuilder(array('allow_extra_fields' =>true))
            ->setAction('/register')
            ->setMethod('POST')
            ->add('name', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Nom')))
            ->add('surname', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Prénom')))
            ->add('mail', EmailType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Email')))
            ->add('phone', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Tél')))
            ->add('address', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Adresse')))
            ->add('cp', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Code postal')))
            ->add('city', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Ville')))
            ->add('password', PasswordType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Mot de passe')))
            ->add('valider', SubmitType::class, array('label' => 'Je crée mon compte !','attr' => array('class' => 'form_submit_button button')))
            ->getForm();

            // Cette partie est la plus importante car c'est elle qui nous permettra de récuperer les infos
            // Saisie par l'user
            $request = Request::createFromGlobals();
            $formRegister->handleRequest($request);

            // On vérifie si le formulaire généré a été envoyé (l'user a cliqué sur le button validé ou submit)
            // L'user a bien saisi les champs demandés !
            // Si c'est le cas on rentre dans la condition...
        
            if($formRegister->isSubmitted() && $formRegister->isValid()){

                // Ici les choses sérieuses commencent !! il faut récupérer les données saisies par l'user...
                $userData = $formRegister->getData();
                $req = new RequiermentsApp();
                if($req->checkPasswordSize($userData['password']) == True){
                    // Si le mot de passe est bon on continue l'ajout du new user
                    // Je teste si je suis bien co sur la bdd
                    $modele = new Modele();
                    $queryResult = $modele->addUser($userData);
                    if($queryResult == 1){
                        return new Response($this->page->render('Login/login.html.twig', array('new_user'=>True, 'login'=>$userData['mail'])));
                    }
                    return new Response($queryResult);
                }
                else{
                    // Sinon
                    // On l'informe que son mot de passe n'est pas top !
                    $warning_pwd = True;
                    return new Response($this->page->render('Register/register.html.twig', array('formRegister'=>$formRegister->createView(), 'warning_pwd'=> $warning_pwd)));
                }

            }
            // On redérige l'user vers la page register pour la première fois lorsqu'il souhaite s'sincrire ou du moins vister la page
            return new Response($this->page->render('Register/register.html.twig', array('formRegister'=>$formRegister->createView())));
        }
    }