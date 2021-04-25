<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\PublishingHouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * 
     * @param AuthorRepository $authorRepository
     * @param BookRepository $bookRepository
     * @param PublishingHouseRepository $phRepository
     * @return Response
     */
    public function index(
            AuthorRepository $authorRepository,
            BookRepository $bookRepository,
            PublishingHouseRepository $phRepository
    ): Response {
        $authorCount = $authorRepository->count([]);
        $publishingHouseCount = $phRepository->count([]);
        $bookCount = $bookRepository->count([]);
        
        return $this->render('home/index.html.twig',[
            'authorCount' => $authorCount,
            'publishingHouseCount' => $publishingHouseCount,
            'bookCount' => $bookCount,
        ]);
    }
}
