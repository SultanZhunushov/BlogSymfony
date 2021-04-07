<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
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
            $file = $request->files->get('form')['attachment'];
            if ($file) {
                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $filename
                );
                $user->setImage($filename);
            }
            $user->setUsername($data['username']);
            $user->setPassword($passwordEncoder->encodePassword($user, $data['password']));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('app_blog_index'));
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
