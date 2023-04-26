<?php

namespace App\Controller;

use App\Entity\Hideout;
use App\Form\HideoutType;
use App\Repository\HideoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HideoutController extends AbstractController
{
    #[Route('admin/hideout/new', name: 'app_hideout_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $hideout = new Hideout();
        $form = $this->createForm(HideoutType::class, $hideout);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($hideout);
            $em->flush();
            return $this->redirectToRoute('app_hideout_list');
        }
        return $this->render('pages/hideout/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/hideout/list', name: 'app_hideout_list')]
    public function list(HideoutRepository $hideoutRepository): Response
    {
        $hideouts = $hideoutRepository->findAll();
        return $this->render('pages/hideout/list.html.twig', [
            'hideouts' => $hideouts,
        ]);
    }

    #[Route('admin/hideout/edit/{id}', name: 'app_hideout_edit')]
    public function edit($id, HideoutRepository $hideoutRepository, EntityManagerInterface $em, Request $request): Response
    {
        $hideout = $hideoutRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(HideoutType::class, $hideout);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_hideout_list');
        }
        return $this->render('pages/hideout/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/hideout/delete/{id}', name: 'app_hideout_delete')]
    public function delete($id, HideoutRepository $hideoutRepository, EntityManagerInterface $em): Response
    {
        $hideout = $hideoutRepository->findOneBy(['id'=> $id]);
        $em->remove($hideout);
        $em->flush();
        return $this->redirectToRoute('app_hideout_list');
    }    
      
}
