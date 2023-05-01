<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatusController extends AbstractController
{
    #[Route('admin/status/new', name: 'app_status_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($status);
            $em->flush();
            $this->addFlash('success', 'Le statut a été créé avec succes');

            return $this->redirectToRoute('app_status_list');
        }
        return $this->render('pages/status/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/status/list', name: 'app_status_list')]
    public function list(StatusRepository $statusRepository): Response
    {
        $statuses = $statusRepository->findAll();
        return $this->render('pages/status/list.html.twig', [
            'statuses' => $statuses,
        ]);
    }

    #[Route('admin/status/edit/{id}', name: 'app_status_edit')]
    public function edit($id, StatusRepository $statusRepository, EntityManagerInterface $em, Request $request): Response
    {
        $type = $statusRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(StatusType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Le statut a été modifié avec succes');

            return $this->redirectToRoute('app_status_list');
        }
        return $this->render('pages/status/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('admin/status/delete/{id}', name: 'app_status_delete')]
    public function delete($id, StatusRepository $statusRepository, EntityManagerInterface $em): Response
    {
        $status = $statusRepository->findOneBy(['id'=> $id]);
        $em->remove($status);
        $em->flush();
        $this->addFlash('success', 'Le statut a été supprimé avec succes');

        return $this->redirectToRoute('app_status_list');
    }  
}
