<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Repository\ListingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(ListingRepository $listingRepository): Response
    {
        $lists = $listingRepository->findAll();
        return $this->render('home/index.html.twig', [
            'lists' => $lists
        ]);
    }

    #[Route('/delete/{id}<\d+>}', name: 'app_list_delete')]
    public function delete(EntityManagerInterface $em, Listing $listing): Response
    {
        $em->remove($listing);
        $em->flush();
        // $this->addFlash('deleteList', 'List deleted successfully');

        return $this->redirectToRoute('app_home');
    }
}
