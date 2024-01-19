<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\SigninFormType;

class connexionController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $auth): Response
    {
        return $this->render('connexion/login.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $auth->getLastUsername(),
            'error' => $auth->getLastAuthenticationError()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout() {
        
    }

    #[Route('/signin', name: 'app_signin', methods: ['GET', 'POST'])]
    function signin(Request $request, EntityManagerInterface $manager) : Response {
        $user = new User();
        $form = $this->createForm(SigninFormType::class, $user);
       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $this->addFlash(
                'success',
                'Signin succefull'
            );
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }
       
        return $this->render('connexion/signin.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
