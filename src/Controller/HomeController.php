<?php

namespace App\Controller;

use App\Repository\MissionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MissionRepository $missionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $missionList =  $missionRepository->findAll();
        $missions = $paginator->paginate(
            $missionList,
            // numero de page
            $request->query->getInt('page', 1),
            // limite par page
            10 
        );

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
