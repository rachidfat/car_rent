<?php


    namespace App\Controller;

    use App\Lib\RequiermentsApp;
    use App\Modele\Modele;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\ButtonType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Twig\Environment;

    class AccountSettingsController extends AbstractController
    {
        /**
         * @var Environment
         */
        private $page;
        public function __construct(Environment $page){

            $this->page = $page;

        }

        public function editAccount()
        {

            $formAccountSettings = $this->createFormBuilder(array('allow_extra_fields' =>true))
            ->setMethod('POST')
            ->add('name', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Nom', 'disabled'=>'disabled', 'value'=>$this->get('session')->get('name'))))
            ->add('surname', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Prénom', 'disabled'=>'disabled', 'value'=>$this->get('session')->get('surname'))))
            ->add('mail', EmailType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Email', 'disabled'=>'disabled', 'value'=>$this->get('session')->get('mail'))))
            ->add('phone', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Tél','value'=>$this->get('session')->get('phone'))))
            ->add('address', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Adresse', 'disabled'=>'disabled', 'value'=>$this->get('session')->get('address'))))
            ->add('cp', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Code postal', 'disabled'=>'disabled', 'value'=>$this->get('session')->get('cp'))))
            ->add('city', TextType::class, array('label' => ' ', 'attr' => array('class' =>'contact_form_subject input_field', 'placeholder'=> 'Ville', 'disabled'=>'disabled', 'value'=>$this->get('session')->get('city'))))
            ->add('change_pwd', ButtonType::class, array('label' => 'Changer le mot de passe','attr' => array('id' => 'updatePassword', 'class' => 'form_submit_button button', 'data-toggle'=> 'modal', 'data-target'=>'#exampleModalCenter')))
            ->add('valider', SubmitType::class, array('label' => 'Je valide les modifications !','attr' => array('class' => 'form_submit_button button')))
            ->getForm();


            
            return new Response($this->page->render('Account/settings.html.twig', array("formAccountSettings"=>$formAccountSettings->createView())));
        }

        public function changePwd(){
            $durableSession = $this->get('session');
            if(!empty($durableSession->get('id'))){

                $requiremnts = new RequiermentsApp();
                if(!$requiremnts->checkPasswordSize($_POST['new_pwd'])){
                    $durableSession->set('pwd_changed', 'KO');
                    return $this->redirect('/accountSettings/'.strtolower($durableSession->get('name')));
                }
                $modele = new Modele();
                $result = $modele->updateUserPwd($_POST);
                if($result == 1){

                    $durableSession->set('pwd', $_POST['new_pwd']);
                    $durableSession->set('pwd_changed', 'OK');
                    return $this->redirect('/accountSettings/'.strtolower($durableSession->get('name')));
                }

                $durableSession->set('pwd_changed', 'KO');
                return $this->redirect('/accountSettings/'.strtolower($durableSession->get('name')));
            }else{
                echo "<h1>401 (Unauthorized) Please login !</h1>";
                return new Response();
            }
        }

    }