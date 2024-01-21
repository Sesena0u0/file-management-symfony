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
use Doctrine\ORM\EntityManagerInterface;

class HomeUserController extends AbstractController
{
    private $nav_folder;
    
    #[Route('/home', name: 'app_home_user')]
    #[IsGranted('ROLE_USER')]
    public function index(FolderRepository $fol, Request $request, EntityManagerInterface $manage) : Response
    {

        //cree un nouveau dossier si il y aune requete de new folder
        $this->new_folder(null, $request, $manage);

        $folderChild = $fol->findBy([
            'user'=>$this->getUser(),
            'folder_child' => null,
        ]);

        $this->nav_folder = null;
        return $this->op_folder($folderChild, $request);
    }

    #[Route('/folder/{id}', name: 'folder_user')]
    #[IsGranted('ROLE_USER')]
    public function folder(Folder $folder, FolderRepository $fol, Request $request, EntityManagerInterface $manage) : Response {
        if($this->getUser() != $folder->getUser()){
            throw $this->createAccessDeniedException();
        }
        
        //cree un nouveau dossier si il y aune requete de new folder
        $this->new_folder($folder, $request, $manage);

        $folderChild = $fol->findBy(['folder_child'=>$folder]);
        
        $this->nav_folder = $folder;
        return $this->op_folder($folderChild, $request);
    }

    private function op_folder($folderChild, $request) : Response {
        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);

        //nom et id des dossier parcouru
        $nav_folder[0] = "";
        $nav_folder[1] = "";

        $now_folder = $this->nav_folder;

        $copy_folderChild = $folderChild;
        try {
            $now_folder = array_pop($copy_folderChild)->getFolderChild();
        } catch (\Throwable $th) {}

        for($i = 0; $i < 100000000000; $i++){
            try{
                $nav_folder[0] = $now_folder->getName().">".$nav_folder[0];
                $nav_folder[1] = $now_folder->getId().">".$nav_folder[1];
                $now_folder = $now_folder->getFolderChild();
            }catch (\Throwable $th) {break;}
        }

        //upload des fichier
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
     
            $file->move('../Upload/', $file->getClientOriginalName());
        }
        
        //transformer les dossier parcouru en objet
        $_navTxt = explode(">", $nav_folder[0]);
        $_navId = explode(">", $nav_folder[1]);
        $nav = array();

        for($i = 0; $i < count($_navId); $i++ ){
            $n = new Nav(intVal($_navId[$i]), $_navTxt[$i]);
            array_push($nav, $n);
        }

        return $this->render('home_user/folder/folder_section.html.twig', [
            'folderChild' => $folderChild,
            'form' => $form->createView(),
            'nav_folder' => $nav,
            'id_folder_now' => ($this->nav_folder != null)? $this->nav_folder->getId() : 0,
        ]);
    }

    // nouveau dosseir
    private function new_folder($folder_, $request, EntityManagerInterface $manage) {
        if ($folder_name = $request->request->get('_new_folder') != null) {
            $folder = new Folder();
            $folder->setName($request->request->get('_new_folder'));
            $folder->setSize(0);
            $folder->setUser($this->getUser());
            $folder->setFolderChild($folder_);

            $this->addFlash(
                'success',
                'new folder created succefull'
            );

            $manage->persist($folder);
            $manage->flush();

        }
    }


    // suppression de fichier
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

Class Nav{
    private $id;
    private $txt;

    public function __construct(int $id, string $txt) {
        $this->id = $id;
        $this->txt = $txt;
    }

    function getId() : int {
        return $this->id;
    }
    function setId(int $id) {
        $this->id = $id;
    }

    function getTxt() : string {
        return $this->txt;
    }
    function setTxt(string $txt) {
        $this->txt = $txt;
    }

}
