<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserInfoController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('user_info/index.html.twig', [
            'controller_name' => 'UserInfoController',
        ]);
    }

    #[Route('/user/edit', name: 'user_edit')]
    #[IsGranted('ROLE_USER')]
    public function editUser(UserRepository $us, Request $request, SessionInterface $session, UserPasswordHasherInterface $hash, EntityManagerInterface $manage): Response
    {

        $user_now = $us->findOneBy(["email" => $this->getUser()->getUserIdentifier() ]);

        $referer = $request->headers->get('referer');
        $session->set('previous_url', $referer);

        $previousUrl = $session->get('previous_url');

        if (!$previousUrl) {
            return $this->redirectToRoute('app_home_user');
        }

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $lastPassword = $request->request->get('lpassword');

        $user_now->setName($name);
        $user_now->setEmail($email);

        if($name == null || $email == null ){
            return $this->redirect($previousUrl);
        }

        if (!$hash->isPasswordValid($user_now, $lastPassword)) {
            return $this->redirect($previousUrl);
        }
        
        if($password != null){
            //$user_now->setPassword($password);
            //$hashedPassword = $hash->hashPassword($user_now, $password);
            $user_now->setPassword($password);
        }

        $manage->persist($user_now);
        $manage->flush();

        return $this->redirectToRoute('app_home_user');
    }
}
