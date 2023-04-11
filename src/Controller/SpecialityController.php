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
    #[Route('/speciality/list', name: 'app_speciality_list')]
    public function list(SpecialityRepository $specialityRepository): Response
    {
        $specialities =  $specialityRepository->findAll();
        return $this->render('pages/speciality/list.html.twig', [
            'specialities' => $specialities,
        ]);
    }

    #[Route('/speciality/new', name: 'app_speciality_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $speciality =  new Speciality();
        $form = $this->createForm(SpecialityType::class, $speciality);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $speciality = $form->getData();
            $em->persist($speciality);
            $em->flush();
            return $this->redirectToRoute('app_speciality_list');
        }
        return $this->render('pages/speciality/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
