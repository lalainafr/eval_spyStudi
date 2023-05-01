<?php

namespace App\Controller;

use App\Entity\Speciality;
use App\Form\SpecialityType;
use App\Repository\SpecialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialityController extends AbstractController
{
    #[Route('admin/speciality/list', name: 'app_speciality_list')]
    public function list(SpecialityRepository $specialityRepository): Response
    {
        $specialities =  $specialityRepository->findAll();
        return $this->render('pages/speciality/list.html.twig', [
            'specialities' => $specialities,
        ]);
    }

    #[Route('admin/speciality/new', name: 'app_speciality_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $speciality =  new Speciality();
        $form = $this->createForm(SpecialityType::class, $speciality);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $speciality = $form->getData();
            $em->persist($speciality);
            $em->flush();
            $this->addFlash('success', 'La specialité a été créée avec succes');

            return $this->redirectToRoute('app_speciality_list');
        }
        return $this->render('pages/speciality/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/speciality/edit/{id}', name: 'app_speciality_edit')]
    public function edit($id, SpecialityRepository $specialityRepository, Request $request, EntityManagerInterface $em): Response
    {
        $speciality =  $specialityRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(SpecialityType::class, $speciality);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La specialité a été modifiée avec succes');

            return $this->redirectToRoute('app_speciality_list');
        }
        return $this->render('pages/speciality/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/speciality/delete/{id}', name: 'app_speciality_delete')]
    public function delete($id, SpecialityRepository $specialityRepository, Request $request, EntityManagerInterface $em): Response
    {
        $speciality =  $specialityRepository->findOneBy(['id' => $id]);
        $em->remove($speciality);
        $em->flush();
        $this->addFlash('success', 'La specialité a été supprimée avec succes');

        return $this->redirectToRoute('app_speciality_list');
    }
}
