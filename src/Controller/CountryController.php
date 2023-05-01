<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{
    #[Route('admin/country/new', name: 'app_country_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($country);
            $em->flush();
            $this->addFlash('success', 'Le pays a été créé avec succes');

            return $this->redirectToRoute('app_country_list');
        }
        return $this->render('pages/country/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/country/list', name: 'app_country_list')]
    public function list(CountryRepository $countryRepository): Response
    {
        $countries = $countryRepository->findAll();
        return $this->render('pages/country/list.html.twig', [
            'countries' => $countries,
        ]);
    }

    #[Route('admin/country/edit/{id}', name: 'app_country_edit')]
    public function edit($id,CountryRepository $countryRepository, EntityManagerInterface $em, Request $request): Response
    {
        $country = $countryRepository->findOneBy(['id'=> $id]);
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Le pays a été modifié avec succes');

            return $this->redirectToRoute('app_country_list');
        }
        return $this->render('pages/country/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('admin/country/delete/{id}', name: 'app_country_delete')]
    public function delete($id, CountryRepository $countryRepository, EntityManagerInterface $em): Response
    {
        $country = $countryRepository->findOneBy(['id'=> $id]);
        $em->remove($country);
        $em->flush();
        $this->addFlash('success', 'Le pays a été supprimé avec succes');

        return $this->redirectToRoute('app_country_list');
    }  
}
