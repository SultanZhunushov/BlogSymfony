<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Security\AppCustomAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(AppCustomAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler,
                            Request $request, UserPasswordEncoderInterface $passwordEncoder, 
                            SluggerInterface $slugger)
    {
        $form = $this->createFormBuilder()
                ->add('username')
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Confirm Password']
                ])
                ->add('attachment', FileType::class, [
                    'mapped' => false
                ])
                ->add('REGISTER', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-success float-right'
                    ]
                ])
                ->getForm();

        $form->handleRequest($request);
        
        if($form->isSubmitted()) {
            $data = $form->getData();
            $user = new User();
            $imageFile = $form->get('attachment')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Image cannot be saved.');
                }
                $user->setImage($newFilename);
            }
            $user->setUsername($data['username']);
            $user->setPassword($passwordEncoder->encodePassword($user, $data['password']));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,        
                $request,
                $authenticator,
                'main'      
            );
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(), 
            'user'=>$this->getUser()
        ]);
    }
    public function register(LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler, Request $request)
    {
        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,          // the User object you just created
            $request,
            $authenticator, // authenticator whose onAuthenticationSuccess you want to use
            'main'          // the name of your firewall in security.yaml
        );
    }
}
