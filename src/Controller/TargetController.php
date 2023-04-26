<?php

namespace App\Controller;

use App\Entity\Target;
use App\Form\TargetType;
use App\Repository\TargetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TargetController extends AbstractController
{
    #[Route('admin/target/new', name: 'app_target_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $target = new Target();
        $form = $this->createForm(TargetType::class, $target);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($target);
            $em->flush();
            return $this->redirectToRoute('app_target_list');
        }
        return $this->render('pages/target/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/target/list', name: 'app_target_list')]
    public function list(TargetRepository $targetRepository): Response
    {
        $targets = $targetRepository->findAll();
        return $this->render('pages/target/list.html.twig', [
            'targets' => $targets,
        ]);
    }

    #[Route('admin/target/edit/{id}', name: 'app_target_edit')]
    public function edit($id, TargetRepository $targetRepository, EntityManagerInterface $em, Request $request): Response
    {
        $target = $targetRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(TargetType::class, $target);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_target_list');
        }
        return $this->render('pages/target/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('admin/target/delete/{id}', name: 'app_target_delete')]
    public function delete($id, TargetRepository $targetRepository, EntityManagerInterface $em): Response
    {
        $target = $targetRepository->findOneBy(['id'=> $id]);
        $em->remove($target);
        $em->flush();
        return $this->redirectToRoute('app_target_list');
    }  
}
