<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/list')]
class ListController extends AbstractController
{
    #[Route('/{id}<\d+>}', name: 'app_list', methods: ['GET'])]
    public function single(Listing $list, TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();
        return $this->render('list/single.html.twig', [
            'id' => $list->getId(),
            'list' => $list,
            'tasks' => $tasks
        ]);
    }

    #[Route('/create', name: 'app_list_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        
        $list = new Listing();
        $form = $this->createForm(ListingType::class, $list);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($list);
            $em->flush();
            
            return $this->redirectToRoute('app_list', ['id' => $list->getId()]);
        }
        return $this->renderForm('list/create.html.twig', [
            'form' => $form,
            'list' => $list,
            'action' => 'Create'
            ]);
    }

    #[Route('/edit/{id<\d+>}', name: 'app_list_edit')]
    public function edit(Listing $list, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ListingType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            // $this->addFlash('success', 'List edited successfully');

            return $this->redirectToRoute('app_list', ['id' => $list->getId()]);
        }
        return $this->renderForm('list/create.html.twig', [
            'form' => $form,
            'list' => $list,
            'action' => 'Edit'
            ]);
    }
}
