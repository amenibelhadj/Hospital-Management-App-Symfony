<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\FitreRecherche;
use App\Entity\ReservationEvenement;
use App\Form\EvenementType;
use App\Form\FitreRechercheType;
use App\Form\ReservationEvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationEvenementRepository;
use App\Services\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/evenement')]
class EvenementController extends AbstractController
{

    #[Route('/test', name: 'app_test')]
    public function indextest(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }



    #[Route('/eventfront', name: 'eventfront', methods: ['GET'])]
    public function eventfront(EvenementRepository $evenementRepository, PaginatorInterface $paginator,Request $request,): Response
    {

        $search = new FitreRecherche();
        $form = $this->createForm(FitreRechercheType::class, $search);
        $form->handleRequest($request);

  $pagination  = $evenementRepository->AfficherEvenement($search);
 //       $pagination  = $evenementRepository->findAll();

        $Events = $paginator->paginate(
            $pagination,
            $request->query->getInt('page', 1), /*page number*/
            3/*limit per page*/
        );
        return $this->render('evenement/evenementFront.html.twig', [
            'evenements' => $Events,
            'form' => $form->createView()

        ]);
    }


    #[Route('/admin', name: 'display_admin')]
    public function indexAdmin(): Response
    {

        return $this->render('Admin/index.html.twig'
        );
    }

    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName= $file->getClientOriginalName();
           try {
                $file->move(
                   $this->getParameter('imgEvent'),
                   $fileName         );
         } catch (FileException $e) {
             // ... handle exception if something happens during file upload
         }

            $evenement->setNbReservation(4);
            $evenement->setEtat("test");
            $evenement->setImage($fileName);
//            $evenement->setImage($fileName);
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idEvent}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {



        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('reserver/{id}', name: 'reserver', methods:  ['GET', 'POST'])]
    public function reserver(Request $request, MailerService $mailerService,ReservationEvenementRepository $reservationEvenementRepository,EvenementRepository $evenementRepository): Response
    {
        $id = $request->get("id");
        $reservationEvenement = new ReservationEvenement();
        $form = $this->createForm(ReservationEvenementType::class, $reservationEvenement);
        $form->handleRequest($request);
        $events = $evenementRepository->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
         $nbplace=   $reservationEvenement->getNbPlace();
            $event=   $reservationEvenement->getIdEvent();

            $contenu="Bonjour mohamed

Vous avez réservez  $nbplace place(s) pour l'événement $event.
Soyez au rendez vous.

L'équipe de SafeEye vous remercie pour votre confiance" ;

            $reservationEvenement->setTotal($events->getTarif()*$reservationEvenement->getNbPlace());
            //calculer la total des réservations (tarif event * nb place de réservations)
            $reservationEvenement->setIdEvent($id);
            //set reservation
            $mailerService->send('Nouvelle reservation','chebbi.mohamed1@esprit.tn','chebbi.mohamed1@esprit.tn'
                ,$contenu);

            $reservationEvenementRepository->save($reservationEvenement, true);

            return $this->redirectToRoute('reservationfront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_evenement/new.html.twig', [
            'reservation_evenement' => $reservationEvenement,
            'form' => $form,
            'evenements' => $evenementRepository->findAll(),

        ]);
    }
}
