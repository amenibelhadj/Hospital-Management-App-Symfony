<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Form\RdvType;
use App\Repository\RdvRepository;
use App\Entity\Disponibilite;

use App\Form\DisponibiliteType;
use App\Repository\DisponibiliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

use App\Entity\PropertySearch;
use App\Form\PropertySearchType;

#[Route('/rdv')]
class RdvController extends AbstractController
{
   
   private $twilio;
   public function __construct(Client $twilio)
   {
    $this->twilio = $twilio;
   }

    #[Route('/', name: 'app_rdv_index', methods: ['GET'])]
    public function index(RdvRepository $rdvRepository): Response
    {
        return $this->render('rdv/index.html.twig', [
            'rdvs' => $rdvRepository->findAll(),
            
        ]);
    }

    #[Route('/gestrdv', name: 'gestrdv', methods: ['GET'])]
    public function gestrdv(RdvRepository $rdvRepository): Response
    {
        return $this->render('rdv/gestrdv.html.twig', [
            'rdvs' => $rdvRepository->findAll(),
            
        ]);
    }

    

 

    #[Route('/new', name: 'app_rdv_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RdvRepository $rdvRepository,
    DisponibiliteRepository $disponibiliteRepository): Response
    {
        
        
        $rdv = new Rdv();
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
            $rdvRepository->save($rdv, true);

            $rdvRepository->add($rdv);
            $message = $this->twilio->messages->create(
               '+21654017791',
               array(
                'from'=>'+13464895283',
                'body'=>'RDV ajouté: 1ere visite: '.$rdv->getPrv().'  Nom_medecin: '.$rdv->getNomMed().'  Date: '.$rdv->getDate()
                
               )
            ); 


            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rdv/new.html.twig', [
            'disponibilites' => $disponibiliteRepository->findAll(),
          
            'rdv' => $rdv,
            'form' => $form,
        ]);


        

            
    }


    #[Route('/{id}', name: 'app_rdv_show', methods: ['GET'])]
    public function show(Rdv $rdv): Response
    {
        return $this->render('rdv/show.html.twig', [
            'rdv' => $rdv,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rdv_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdvRepository->save($rdv, true);

            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rdv/edit.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rdv_delete', methods: ['POST'])]
    public function delete(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rdv->getId(), $request->request->get('_token'))) {
            $rdvRepository->remove($rdv, true);
        }

        return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
    }
}
