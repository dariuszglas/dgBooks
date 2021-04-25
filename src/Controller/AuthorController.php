<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/author", name="author.")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->orderedFindAll();
        
        return $this->render('author/index.html.twig', [
            'authors' => $authors
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
        $author = new Author();
        
        return $this->showAuthorForm($author, $request);
    }
    
    /**
     * @Route("/edit/{id}", name="edit")
     * 
     * @param Author $author
     * @param Request $request
     * 
     * @return Response
     */
    public function edit(Author $author, Request $request): Response
    {
        $flashMsg = 'Author data has been updated';
        return $this->showAuthorForm($author, $request, $flashMsg);
    }
    
    /**
     * 
     * @param Author $author
     * @param Request $request
     * @param string $flashMsg
     * 
     * @return Response
     */
    private function showAuthorForm(Author $author, Request $request, string $flashMsg = 'Author has been created'): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            
            $this->addFlash('success', $flashMsg);
            
            return $this->redirect($this->generateUrl('author.index'));
        }
        
        return $this->render('author/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * 
     * @param Author $author
     * @return JsonResponse
     */
    public function remove(Author $author): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($author);
        $em->flush();
        
        return new JsonResponse([
            'success' => true
        ]);
    }
}
