<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Listing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/task')]
class TaskController extends AbstractController
{
    
    #[Route('/create', name: 'app_task_create')]
    public function create(Request $request, EntityManagerInterface $em, Listing $listing): Response
    {
        $task = new Task(); //Déclaration d'une nouvelle instance de l'entité Task
        // $list = Listing::getTasks();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $repo = $em->getRepository(Listing::class);
            $em->persist($task);
            $em->flush();
            
            return $this->redirectToRoute('app_list', ['id' => $listing->getId()]);
        }
        
        return $this->renderForm('task/create.html.twig', [
            'form' => $form,
            'task' => $task,
            'action' => 'Create'
            ]);
    }

    #[Route('/edit/{id<\d+>}', name: 'app_task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em, Listing $listing): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $em->getRepository(Listing::class);
            $em->flush();

            return $this->redirectToRoute('app_list', ['id' => $listing->getId()]);
        }
        return $this->renderForm('task/create.html.twig', [
            'form' => $form,
            'task' => $task,
            'action' => 'Edit'
            ]);
    }

    #[Route('/done/{id<\d+>}', name: 'app_task_done')]
    public function done(Task $task, Request $request, EntityManagerInterface $em, Listing $listing): Response
    {
        $form = $this->createForm(DoneTaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $em->getRepository(Listing::class);
            $em->flush();

            return $this->redirectToRoute('app_list', ['id' => $listing->getId()]);
        }
        return $this->renderForm('list/single.html.twig', [
            'form' => $form,
            'task' => $task,
            ]);
    }

    #[Route('/delete/{id}<\d+>}', name: 'app_alldonetask_delete')]
    public function delete(EntityManagerInterface $em, Task $task, Listing $listing): Response
    {
        foreach ($listing as $list){
            $list->getTasks();
            if ($task->isDone()){
                $list->removeTask($task);
                $em->flush();
                // $this->addFlash('deleteList', 'Task deleted successfully');
            }
        }
        

        return $this->redirectToRoute('app_list', ['id' => $listing->getId()]);
    }
}
