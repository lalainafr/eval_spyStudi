<?php

namespace App\Controller;

use App\Repository\MissionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MissionRepository $missionRepository): Response
    {
        $missions =  $missionRepository->findAll();
        return $this->render('pages/home/index.html.twig', [
            'missions' => $missions,
        ]);
    }

      

    #[Route('/detail/{id}', name: 'app_home_detail')]
    public function detail(MissionRepository $missionRepository, $id): Response
    {
        $mission =  $missionRepository->findOneBy(['id' => $id]);
        return $this->render('pages/home/detail.html.twig', [
            'mission' => $mission,
        ]);
    }
}
