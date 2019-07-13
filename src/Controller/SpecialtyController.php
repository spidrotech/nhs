<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Specialty;
use App\Form\SpecialtyType;

/**
 * Specialty controller.
 * @Route("/api", name="api_")
 */
class SpecialtyController extends FOSRestController
{

      /**
   * Lists all specialties.
   * @Rest\Get("/specialties", name="get collection of specialties")
   *
   * @return Response
   */
    public function getSpecialtiesAction()
    {
    	$repository = $this->getDoctrine()->getRepository(Specialty::class);
	    $specialties = $repository->findall();
	    return $this->handleView($this->view($specialties));
    }

      /**
   * Get a specialty.
   * @Rest\Get("/specialty/{id}", name="get resource specialties")
   *
   * @return Response
   */
    public function getSpecialtyAction(Request $request)
    {
    	$repository = $this->getDoctrine()->getRepository(Specialty::class);
	    $specialty = $repository->find($request->get('id'));
	    if (empty($specialty)) {
      		return $this->handleView($this->view(['status' => 'ko'], Response::HTTP_NOT_FOUND));
        }
	    return $this->handleView($this->view($specialty));
    }
    /**
   * Create Specialty.
   * @Rest\Post("/specialty", name="post specialty")
   *
   * @return Response
   */
  public function postSpecialtyAction(Request $request)
  {
    $specialty = new Specialty();
    $form = $this->createForm(SpecialtyType::class, $specialty);

    $data = json_decode($request->getContent(), true);
    $form->submit($data);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($specialty);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }
}
