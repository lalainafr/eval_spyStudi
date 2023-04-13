<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MissionController extends AbstractController
{
    #[Route('/mission/list', name: 'app_mission_list')]
    public function list(MissionRepository $missionRepository): Response
    {
        $missions =  $missionRepository->findAll();
        return $this->render('pages/mission/list.html.twig', [
            'missions' => $missions,
        ]);
    }

    #[Route('/mission/new', name: 'app_mission_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $mission =  new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $agent = $form->getData();
            $em->persist($mission);
            $em->flush();
            return $this->redirectToRoute('app_mission_list');
        }
        return $this->render('pages/mission/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
