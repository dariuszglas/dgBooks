<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book", name="book.")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->orderedFindAll();
        
        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }
    
    /**
     * @Route("/create", name="create")
     * 
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $book = new Book();
        
        return $this->showForm($book, $request);
    }
    
    /**
     * @Route("/edit/{id}", name="edit")
     * 
     * @param Book $book
     * @param Request $request
     * 
     * @return Response
     */
    public function edit(Book $book, Request $request): Response
    {
        $flashMsg = 'Book data has been updated';
        $book->setIsbnField($book->getIsbn());
        return $this->showForm($book, $request, $flashMsg);
    }
    
    /**
     * 
     * @param Book $book
     * @param Request $request
     * @param string $flashMsg
     * 
     * @return Response
     */
    private function showForm(Book $book, Request $request, string $flashMsg = 'Book has been created'): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            
            $this->addFlash('success', $flashMsg);
            
            return $this->redirect($this->generateUrl('book.index'));
        }
        
        return $this->render('book/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * 
     * @param Book $book
     * @return JsonResponse
     */
    public function remove(Book $book): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($book);
        $em->flush();
        
        return new JsonResponse([
            'success' => true
        ]);
    }
}
