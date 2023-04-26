<?php

namespace App\Controller;

use App\Data\Search;
use App\Form\SearchType;
use App\Repository\MissionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MissionRepository $missionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Creer le formulaire de recherche
        $data = new Search();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // récuperer les données du filtre
        $missionList =  $missionRepository->findSearch($data);
        $missions = $paginator->paginate(
            $missionList,
            // numero de page
            $request->query->getInt('page', 1),
            // limite par page
            10
        );

        // S'il y a une requete AJAX ('ajax' dans l'url)
        // On renvoie un JSON pour avoir le contenu des données filtrées
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' =>  $this->renderView('pages/home/_content.html.twig', [
                    'missions' => $missions,
                    'form' => $form->createView(),
                ])
            ]);
        }

        return $this->render('pages/home/index.html.twig', [
            'missions' => $missions,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/home/detail/{id}', name: 'app_home_detail')]
    public function detail(MissionRepository $missionRepository, $id): Response
    {
        $mission =  $missionRepository->findOneBy(['id' => $id]);
        return $this->render('pages/home/detail.html.twig', [
            'mission' => $mission,
        ]);
    }
}
