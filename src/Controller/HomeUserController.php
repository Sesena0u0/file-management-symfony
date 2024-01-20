<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Form\FileFormType;
use App\Repository\FolderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class HomeUserController extends AbstractController
{
    #[Route('/home', name: 'app_home_user')]
    #[IsGranted('ROLE_USER')]
    public function index(FolderRepository $fol, Request $request): Response
    {

        $folder = $fol->findBy(['user'=>$this->getUser()]);

        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
    
            $file->move('../Upload/', $file->getClientOriginalName());
        }

        return $this->render('home_user/index.html.twig', [
            'folder' => $folder,
            'form' => $form->createView(),
        ]);
    }

    // Fonction de suppression de fichier
    private function supprimerFichier($lienFichier): void
    {
        try {
            if (file_exists($lienFichier)) {
                unlink($lienFichier);
            }
        } catch (\Exception $e) {
            
        }
    }
}
