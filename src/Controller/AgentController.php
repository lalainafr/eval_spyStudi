<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Repository\AgentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgentController extends AbstractController
{
    #[Route('admin/agent/list', name: 'app_agent_list')]
    public function list(AgentRepository $agentRepository): Response
    {
        $agents =  $agentRepository->findAll();
        return $this->render('pages/agent/list.html.twig', [
            'agents' => $agents,
        ]);
    }

    #[Route('admin/agent/new', name: 'app_agent_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $agent =  new Agent();
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $agent = $form->getData();
            $em->persist($agent);
            $em->flush();
            return $this->redirectToRoute('app_agent_list');
        }
        return $this->render('pages/agent/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/agent/edit/{id}', name: 'app_agent_edit')]
    public function edit($id, AgentRepository $agentRepository, EntityManagerInterface $em, Request $request): Response
    {
        $agent = $agentRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_agent_list');
        }
        return $this->render('pages/agent/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('admin/agent/delete/{id}', name: 'app_agent_delete')]
    public function delete($id, AgentRepository $agentRepository, EntityManagerInterface $em, Request $request): Response
    {
        $agent = $agentRepository->findOneBy(['id' => $id]);
        $em->remove($agent);
        $em->flush();
        return $this->redirectToRoute('app_agent_list');
        
    }
}
