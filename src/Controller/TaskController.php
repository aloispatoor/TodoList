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
    public function create(Request $request, EntityManagerInterface $em, Listing $list): Response
    {
        $task = new Task(); //Déclaration d'une nouvelle instance de l'entité Task
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $task->setList($list);
            $em->persist($task);
            $em->flush();
            
            return $this->redirectToRoute('app_list', ['id' => $list->getId()]);
        }
        
        return $this->renderForm('task/create.html.twig', [
            'form' => $form,
            'task' => $task,
            'action' => 'Create'
            ]);
    }

    #[Route('/edit/{id<\d+>}', name: 'app_task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em, Listing $list): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setList($list);
            $em->flush();
            // $this->addFlash('success', 'Task edited successfully');

            return $this->redirectToRoute('app_list');
        }
        return $this->renderForm('task/create.html.twig', [
            'form' => $form,
            'task' => $task,
            'action' => 'Edit'
            ]);
    }

    #[Route('/delete/{id}<\d+>}', name: 'app_alldonetask_delete')]
    public function delete(EntityManagerInterface $em, Task $task, Listing $lists): Response
    {
        foreach($lists as $list){
            $list->getTasks();
            if($task->isDone()){
                $em->remove($task);
                $em->flush();
                // $this->addFlash('deleteList', 'Task deleted successfully');
            }
        }
        

        return $this->redirectToRoute('app_list', ['id' => $lists->getId()]);
    }
}
