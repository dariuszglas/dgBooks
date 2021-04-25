<?php

namespace App\Controller;

use App\Entity\PublishingHouse;
use App\Form\PublishingHouseType;
use App\Repository\PublishingHouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publishing_house", name="publishing_house.")
 */
class PublishingHouseController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param PublishingHouseRepository $publishingHouseRepository
     * @return Response
     */
    public function index(PublishingHouseRepository $publishingHouseRepository): Response
    {
        $publishingHouses = $publishingHouseRepository->orderedFindAll();
        
        return $this->render('publishing_house/index.html.twig', [
            'publishingHouses' => $publishingHouses
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
        $publishingHouse = new PublishingHouse();
        
        return $this->showForm($publishingHouse, $request);
    }
    
    /**
     * @Route("/edit/{id}", name="edit")
     * 
     * @param PublishingHouse $publishingHouse
     * @param Request $request
     * 
     * @return Response
     */
    public function edit(PublishingHouse $publishingHouse, Request $request): Response
    {
        $flashMsg = 'Publishing house data has been updated';
        return $this->showForm($publishingHouse, $request, $flashMsg);
    }
    
    /**
     * 
     * @param PublishingHouse $publishingHouse
     * @param Request $request
     * @param string $flashMsg
     * 
     * @return Response
     */
    private function showForm(PublishingHouse $publishingHouse, Request $request, string $flashMsg = 'Publishing house has been created'): Response
    {
        $form = $this->createForm(PublishingHouseType::class, $publishingHouse);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($publishingHouse);
            $em->flush();
            
            $this->addFlash('success', $flashMsg);
            
            return $this->redirect($this->generateUrl('publishing_house.index'));
        }
        
        return $this->render('publishing_house/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * 
     * @param PublishingHouse $publishingHouse
     * @return JsonResponse
     */
    public function remove(PublishingHouse $publishingHouse): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($publishingHouse);
        $em->flush();
        
        return new JsonResponse([
            'success' => true
        ]);
    }
}
