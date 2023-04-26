<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TypeController extends AbstractController
{
    #[Route('admin/type/new', name: 'app_type_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);
            $em->flush();
            return $this->redirectToRoute('app_type_list');
        }
        return $this->render('pages/type/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/type/list', name: 'app_type_list')]
    public function list(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();
        return $this->render('pages/type/list.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('admin/type/edit/{id}', name: 'app_type_edit')]
    public function edit($id, TypeRepository $typeRepository, EntityManagerInterface $em, Request $request): Response
    {
        $type = $typeRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_type_list');
        }
        return $this->render('pages/type/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/type/delete/{id}', name: 'app_type_delete')]
    public function delete($id, TypeRepository $typeRepository, EntityManagerInterface $em): Response
    {
        $type = $typeRepository->findOneBy(['id'=> $id]);
        $em->remove($type);
        $em->flush();
        return $this->redirectToRoute('app_type_list');

    }
}
