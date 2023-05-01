<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MissionController extends AbstractController
{

    #[Route('admin/mission/list', name: 'app_mission_list')]
    public function list(MissionRepository $missionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $missionList =  $missionRepository->findAll();
        $missions = $paginator->paginate(
            $missionList,
            // numero de page
            $request->query->getInt('page', 1),
            // limite par page
            10
        );
        return $this->render('pages/mission/list.html.twig', [
            'missions' => $missions,
        ]);
    }

    #[Route('admin/mission/new', name: 'app_mission_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $mission =  new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mission = $form->getData();

            // ---- VERIFICATION CODE METIER ---- 

            // <-- AGENT vs CIBLES (nationalité différente) -->
            // Recuperer la nationalité des agents et des cibles
            $agents = $mission->getAgent();
            foreach ($agents as $agent) {
                $agentNatioanlityArray[] = $agent->getNationality()->getName();
            };
            $targets = $mission->getTarget();
            foreach ($targets as $target) {
                $targetNatioanlityArray[] = $target->getNationality()->getName();
            };

            // Vérifier un des agents a la meme nationalité que les cibles
            if ($this->isAgentSameNationalityAsTarget($agentNatioanlityArray, $targetNatioanlityArray)) {
                $this->addFlash('error', 'Les agents ne peuvent pas avoir le mêmes nationalités que les cibles');
                return $this->redirectToRoute('app_mission_new');
            }

            // <-- CONTACT vs MISSION (meme pays ) --> 
            // Recuperer la nationalité des contact et de la mission
            $contacts = $mission->getContact();
            foreach ($contacts as $contact) {
                $contactNatioanlityArray[] = $contact->getNationality()->getName();
            };
            $missionCountry = $mission->getCountry()->getName();

            // Vérifier si un des contacts n'est pas du meme pays que la mission)
            if ($this->isContactSameCountryAsMission($contactNatioanlityArray, $missionCountry)) {
                $this->addFlash('error', 'Les contacts doivent être du même pays que la mission');
                return $this->redirectToRoute('app_mission_new');
            }

            // <-- PLANQUE vs MISSION (meme pays ) --> 
            // Recuperer le pays des planques et de la mission
            $hideOuts = $mission->getHideOut();
            foreach ($hideOuts as $hideOut) {
                $hideOutCountryArray[] = $hideOut->getCountry()->getName();
            };
            $missionCountry = $mission->getCountry()->getName();

            // Vérifier si une des planques n'est pas du meme pays que la mission)
            if ($this->isHideOutSameCountryAsMission($hideOutCountryArray, $missionCountry)) {
                $this->addFlash('error', 'Les planques doivent être du même pays que la mission');
                return $this->redirectToRoute('app_mission_new');
            }

            // <-- AGENT vs MISSION (au moins 1 agent disposant la spécialité requise ) --> 
            // Recuperer les specialités des agents et de la mission
            $agents = $mission->getAgent();
            foreach ($agents as $key => $agent) {
                $agentSpecialities[] = ($agent->getSpeciality());
                foreach ($agentSpecialities as $specialities) {
                    foreach ($specialities as $speciality) {
                        $agentSpecialitiesList[] = $speciality->getName();
                    }
                }
            }
            $missionSpeciality = $mission->getSpeciality()->getName();

            // Vérifier si au moins 1 agent dispose de la specialité requise)
            if($this->hasAgentRequidedMissionSpeciality($agentSpecialitiesList, $missionSpeciality)){
                 $this->addFlash('error', 'Au moins 1 agent doit disposer de la spécialité requise');
                 return $this->redirectToRoute('app_mission_new');
             }

            $em->persist($mission);
            $em->flush();

            $this->addFlash('success', 'La mission a été créée avec succes');

            return $this->redirectToRoute('app_mission_list');
        }
        return $this->render('pages/mission/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/mission/edit/{id}', name: 'app_mission_edit')]
    public function edit($id, Request $request, EntityManagerInterface $em, MissionRepository $missionRepository): Response
    {
        $mission =  $missionRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mission = $form->getData();

            // ---- VERIFICATION CODE METIER ---- 

            // <-- AGENT vs CIBLES (nationalité différente) -->
            // Recuperer la nationalité des agents et des cibles
            $agents = $mission->getAgent();
            foreach ($agents as $agent) {
                $agentNatioanlityArray[] = $agent->getNationality()->getName();
            };
            $targets = $mission->getTarget();
            foreach ($targets as $target) {
                $targetNatioanlityArray[] = $target->getNationality()->getName();
            };
            // Vérifier un des agents a la meme nationalité que les cibles
            if ($this->isAgentSameNationalityAsTarget($agentNatioanlityArray, $targetNatioanlityArray)) {
                $this->addFlash('error', 'Les agents ne peuvent pas avoir le mêmes nationalités que les cibles');
                return $this->redirectToRoute('app_mission_edit', ['id' => $mission->getId()]);
            }

            // <-- CONTACT vs MISSION (meme pays ) --> 
            // Recuperer la nationalité des contact et de la mission
            $contacts = $mission->getContact();
            foreach ($contacts as $contact) {
                $contactNatioanlityArray[] = $contact->getNationality()->getName();
            };
            $missionCountry = $mission->getCountry()->getName();

            // Vérifier si un des contacts n'est pas du meme pays que la mission)
            if ($this->isContactSameCountryAsMission($contactNatioanlityArray, $missionCountry)) {
                $this->addFlash('error', 'Les contacts doivent être du même pays que la mission');
                return $this->redirectToRoute('app_mission_edit', ['id' => $mission->getId()]);
            }

            // <-- PLANQUE vs MISSION (meme pays ) --> 
            // Recuperer le pays des planques et de la mission
            $hideOuts = $mission->getHideOut();
            foreach ($hideOuts as $hideOut) {
                $hideOutCountryArray[] = $hideOut->getCountry()->getName();
            };
            $missionCountry = $mission->getCountry()->getName();

            // Vérifier si une des planques n'est pas du meme pays que la mission)
            if ($this->isHideOutSameCountryAsMission($hideOutCountryArray, $missionCountry)) {
                $this->addFlash('error', 'Les planques doivent être du même pays que la mission');
                return $this->redirectToRoute('app_mission_edit', ['id' => $mission->getId()]);
            }

            // <-- AGENT vs MISSION (au moins 1 agent disposant la spécialité requise ) --> 
            // Recuperer les specialités des agents et de la mission
            $agents = $mission->getAgent();
            foreach ($agents as $key => $agent) {
                $agentSpecialities[] = ($agent->getSpeciality());
                foreach ($agentSpecialities as $specialities) {
                    foreach ($specialities as $speciality) {
                        $agentSpecialitiesList[] = $speciality->getName();
                    }
                }
            }
            $missionSpeciality = $mission->getSpeciality()->getName();

            // Vérifier si au moins 1 agent dispose de la specialité requise)
            if($this->hasAgentRequidedMissionSpeciality($agentSpecialitiesList, $missionSpeciality)){
                 $this->addFlash('error', 'Au moins 1 agent doit disposer de la spécialité requise');
                 return $this->redirectToRoute('app_mission_edit', ['id' => $mission->getId()]);
                }

            $em->flush();

            $this->addFlash('success', 'La mission a été modifiée avec succes');

            return $this->redirectToRoute('app_mission_list');
        }
        return $this->render('pages/mission/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/mission/delete/{id}', name: 'app_mission_delete')]
    public function remove($id, EntityManagerInterface $em, MissionRepository $missionRepository): Response
    {
        $mission =  $missionRepository->findOneBy(['id' => $id]);
        $em->remove($mission);
        $em->flush();
        $this->addFlash('success', 'La mission a été supprimée avec succes');

        return $this->redirectToRoute('app_mission_list');
    }

    public function isAgentSameNationalityAsTarget($array, $array1)
    {
        foreach ($array as $agent) {
            if (in_array($agent, $array1)) {
                return true;
            }
        }
    }

    public function isContactSameCountryAsMission($array, $missionCountry)
    {
        foreach ($array as $contact) {
            if ($contact != $missionCountry) {
                return true;
            }
        }
    }

    public function isHideOutSameCountryAsMission($array, $country)
    {
        foreach ($array as $hideOut) {
            if ($hideOut != $country) {
                return true;
            }
        }
    }

    public function hasAgentRequidedMissionSpeciality($array, $speciality)
    {
            if (!in_array($speciality, $array)) {
                return true;
            }
    }
}
