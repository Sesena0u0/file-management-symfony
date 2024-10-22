<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Entity\File;
use App\Form\FileFormType;
use App\Repository\FolderRepository;
use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeUserController extends AbstractController
{
    private $nav_folder;
    private $folderChild;
    private $fileChild;
    private $manage;
    private $request;
    private $all_folder;
    
    #[Route('/home', name: 'app_home_user')]
    #[IsGranted('ROLE_USER')]
    public function index(FolderRepository $fol, FileRepository $fil, Request $request, EntityManagerInterface $manage, SessionInterface $session) : Response
    {

        //cree un nouveau dossier si il y aune requete de new folder
        $this->new_folder(null, $request, $manage);

        //
        $this->all_folder($fol);

        $folderChild = $fol->findBy([
            'user'=>$this->getUser(),
            'folder_child' => null,
        ]);


        $this->nav_folder = null;

        $fileChild = $fil->findBy([
            'user'=>$this->getUser(),
            'folder'=> $this->nav_folder,
        ]);

        $referer = $request->headers->get('referer');
        $session->set('previous_url', $referer);

        $this->request = $request;
        $this->manage = $manage;

        return $this->op_folder($folderChild, $fileChild, $this->nav_folder);
    }

    #[Route('/folder/{id}', name: 'folder_user')]
    #[IsGranted('ROLE_USER')]
    public function folder(Folder $folder, FolderRepository $fol, FileRepository $fil, Request $request, EntityManagerInterface $manage, SessionInterface $session) : Response {
        if($this->getUser() != $folder->getUser()){
            throw $this->createAccessDeniedException();
        }
        
        //cree un nouveau dossier si il y aune requete de new folder
        $this->new_folder($folder, $request, $manage);

        $this->all_folder($fol);

        $folderChild = $fol->findBy(['folder_child'=>$folder]);

        $this->nav_folder = $folder;

        $fileChild = $fil->findBy([
            'user'=>$this->getUser(),
            'folder'=> $this->nav_folder,
        ]);

        $referer = $request->headers->get('referer');
        $session->set('previous_url', $referer);

        $this->request = $request;
        $this->manage = $manage;
        
        return $this->op_folder($folderChild, $fileChild, $this->nav_folder);
    }

    private function op_folder($folderChild, $fil, $file_selected) : Response {
        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($this->request);

        $this->folderChild = $folderChild;
        $this->fileChild = $fil;

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
     
            $storage = '../Upload/';
            $array_file = explode(".", $file->getClientOriginalName());
            $file->move($storage, $file->getClientOriginalName());

            $file_ext = $array_file[count($array_file)-1];
            $file_name = "";

            for($i = 0; $i < count($array_file)-1; $i++){
                $file_name .= $array_file[$i];
            }

            $link_file = $storage.$file_name.'.'.$file_ext;
            $file_ = new File();

            $file_->setLink($link_file);
            $file_->setFolder($this->nav_folder);
            $file_->setSize(0);
            $file_->setExt($file_ext);
            $file_->setName($file_name);
            $file_->setUser($this->getUser());

            $this->manage->persist($file_);
            $this->manage->flush();

        }
        
        //transformer les dossier parcouru en objet
        $_navTxt = explode(">", $nav_folder[0]);
        $_navId = explode(">", $nav_folder[1]);
        $nav = array();

        for($i = 0; $i < count($_navId); $i++ ){
            $n = new Nav(intVal($_navId[$i]), $_navTxt[$i]);
            array_push($nav, $n);
        }

        $last_nav = $nav[0];
        if(count($nav) > 2){
            $last_nav =$nav[count($nav)-2];
        }

        return $this->render('home_user/folder/folder_section.html.twig', [
            'folderChild' => $folderChild,
            'files'=> $fil,
            'form' => $form->createView(),
            'nav_folder' => $nav,
            'last_nav_folder' => $last_nav,
            'folder_now' => $this->nav_folder,
            'id_folder_now' => ($this->nav_folder != null)? $this->nav_folder->getId() : 0,
            'all_folder' => $this->all_folder,
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

    #[Route('/read/{id}', name: 'read_file_user')]
    #[Route('/file/{id}', name: 'read_file_user_2')]
    #[IsGranted('ROLE_USER')]
    public function readfile(File $file) {

        if($this->getUser() != $file->getUser()){
            throw $this->createAccessDeniedException();
        }

        if($file->getExt() == "jpg"){
            header('Content-Type: image/jpg');
        }else if($file->getExt() == "png"){
            header('Content-Type: image/png');
        }else if($file->getExt() == "txt"){
            header('Content-Type: text/txt');
        }else if($file->getExt() == "gif"){
            header('Content-Type: image/gif');
        }else if($file->getExt() == "pdf"){
            header('Content-Type: txt/pdf');
        }

        readfile($file->getLink());
        exit;
    }

    #[Route('/edit_folder/{id}', name: 'edit_folder')]
    #[IsGranted('ROLE_USER')]
    public function edit_folder(Folder $folder, Request $request, EntityManagerInterface $manage, SessionInterface $session) {
        if($this->getUser() != $folder->getUser()){
            throw $this->createAccessDeniedException();
        }

        $referer = $request->headers->get('referer');
        $session->set('previous_url', $referer);

        $previousUrl = $session->get('previous_url');

        if (!$previousUrl) {
            return $this->redirectToRoute('app_home_user');
        }

        $folder_name = $request->request->get('name');
        if($folder_name == null){
            return $this->redirect($previousUrl);
        }

        $folder->setName($folder_name);

        $manage->persist($folder);
        $manage->flush();
       
        return $this->redirect($previousUrl);

    }

    #[Route('/edit_file/{id}', name: 'edit_file')]
    #[IsGranted('ROLE_USER')]
    public function edit_file(File $file, Request $request, EntityManagerInterface $manage, SessionInterface $session) {
        if($this->getUser() != $file->getUser()){
            throw $this->createAccessDeniedException();
        }

        $referer = $request->headers->get('referer');
        $session->set('previous_url', $referer);

        $previousUrl = $session->get('previous_url');

        if (!$previousUrl) {
            return $this->redirectToRoute('app_home_user');
        }

        $file_name = $request->request->get('_name');
        if($file_name == null){
            return $this->redirect($previousUrl);
        }

        $manage->persist($file);
        $manage->flush();
        
        return $this->redirect($previousUrl);

    }

    #[Route('/delete_folder/{id}', name: 'delete_folder')]
    #[IsGranted('ROLE_USER')]
    public function delete_folder(Folder $folder, EntityManagerInterface $entityManager, Request $request, EntityManagerInterface $manage, SessionInterface $session) {
        if($this->getUser() != $folder->getUser()){
            throw $this->createAccessDeniedException();
        }

        // stocke l'url dernier dans la session
        $referer = $request->headers->get('referer');
        $session->set('previous_url', $referer);

        $entityManager->remove($folder);
        $entityManager->flush();

        // redirige vers url precedant
        $previousUrl = $session->get('previous_url');

        if (!$previousUrl) {
            return $this->redirectToRoute('app_home_user');
        }

        return $this->redirect($previousUrl);
    }

    #[Route('/delete_file/{id}', name: 'delete_file')]
    #[IsGranted('ROLE_USER')]
    public function delete_file(File $file, EntityManagerInterface $entityManager, FolderRepository $fol, FileRepository $fil, Request $request, EntityManagerInterface $manage, SessionInterface $session) : Response {
        if($this->getUser() != $file->getUser()){
            throw $this->createAccessDeniedException();
        }

        try {
            if (file_exists($file->getLink())) {
                unlink($file->getLink());
            }
        } catch (\Exception $e) {
            dd("file missing");
        }

        // stocke l'url dernier dans la session
        $referer = $request->headers->get('referer');
        $session->set('previous_url', $referer);

        $entityManager->remove($file);
        $entityManager->flush();

        // redirige vers url precedant
        $previousUrl = $session->get('previous_url');

        if (!$previousUrl) {
            return $this->redirectToRoute('app_home_user');
        }

        return $this->redirect($previousUrl);

    }

    private function all_folder($fol){
        $this->all_folder = $fol->findBy(['user'=>$this->getUser()]);
        foreach ($this->all_folder as $folder) {
            $enfant =  $fol->findBy([
                'user'=>$this->getUser(),
                'folder_child' => $folder,
            ]);

            $folder->setEnfant($enfant);
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
