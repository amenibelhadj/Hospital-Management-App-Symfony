<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Entity\Reclamation;
use App\Form\PropertySearchType;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use ConsoleTVs\Profanity\Builder;
use ConsoleTVs\Profanity\Classes\Blocker;
use ConsoleTVs\Profanity\Facades\Profanity;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use ConsoleTVs\Profanity\ProfanityServiceProvider;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    private $twilio;

    public function __construct(Client $twilio)
    {
        $this->twilio = $twilio;
    }

    #[Route('/', name: 'app_reclamation_index', methods: ['GET','POST'])]
    public function index(ReclamationRepository $reclamationRepository,Request $request): Response
    {
$propertySearch = new PropertySearch();
$form = $this->createForm(PropertySearchType::class,$propertySearch);
$form->handleRequest($request);


$reclamation= $reclamationRepository->findAll();

if ($form->isSubmitted() && $form->isValid()) {
    $nom = $propertySearch->getNom();
    if ($nom != "")
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findBy(['email' => $nom]);
    else
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
}
        return $this->render('reclamation/index.html.twig', [
            'form' => $form->createView(),'reclamations'=>$reclamation]);
    }





    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $reclamationRepository->save($reclamation, true);



            $reclamationRepository->add($reclamation);
            $message = $this->twilio->messages->create(
                '+21627622893',
                array(
                   'from' => '+18107887494',
                   'body' => 'Hello from SafeEye' .$reclamation->getPrenom().'Ta réclamation est envoyé'
                )
            );

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }


}
