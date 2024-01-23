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
        
        if($password != null){
            $user_now->setPassword($password);
        }

        /*$user_tmp = new User();
        $user_tmp->setName($user_now->getName());
        $user_tmp->setEmail($user_now->getEmail());
        $user_tmp->setRoles($user_now->getRoles());
        $user_tmp->setCreatedAt($user_now->getCreatedAt());

        $hashpassword = $hash->hashPassword(
            $user_tmp,
            $lastPassword
        );

        if($hashpassword == $user_now->getPassword()){
            dd($user_now);
        }
        dd($hashpassword);*/

        $manage->persist($user_now);
        $manage->flush();

        return $this->redirectToRoute('app_home_user');
    }
}
