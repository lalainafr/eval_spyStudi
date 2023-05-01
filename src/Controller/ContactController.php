<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('admin/contact/new', name: 'app_contact_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', 'Le contact a été créé avec succes');

            return $this->redirectToRoute('app_contact_list');
        }
        return $this->render('pages/contact/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/contact/list', name: 'app_contact_list')]
    public function list(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();
        return $this->render('pages/contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('admin/contact/edit/{id}', name: 'app_contact_edit')]
    public function edit($id, ContactRepository $contactRepository, EntityManagerInterface $em, Request $request): Response
    {
        $contact = $contactRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Le contact a été modifié avec succes');

            return $this->redirectToRoute('app_contact_list');
        }
        return $this->render('pages/contact/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('admin/contact/delete/{id}', name: 'app_contact_delete')]
    public function delete($id, ContactRepository $contactRepository, EntityManagerInterface $em): Response
    {
        $contact = $contactRepository->findOneBy(['id'=> $id]);
        $em->remove($contact);
        $em->flush();
        $this->addFlash('success', 'Le contact a été supprimé avec succes');

        return $this->redirectToRoute('app_contact_list');
    }  
}
