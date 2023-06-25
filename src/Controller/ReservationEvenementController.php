<?php

namespace App\Controller;

use App\Entity\ReservationEvenement;
use App\Form\ReservationEvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationEvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/reservationevenement')]
class ReservationEvenementController extends AbstractController
{
    #[Route('/', name: 'app_reservation_evenement_index', methods: ['GET'])]
    public function index(ReservationEvenementRepository $reservationEvenementRepository): Response
    {
        dump($reservationEvenementRepository->findAll());
        return $this->render('reservation_evenement/index.html.twig', [
            'reservation_evenements' => $reservationEvenementRepository->findAll(),
        ]);

    }

    #[Route('/reservationfront', name: 'reservationfront', methods: ['GET'])]
    public function reservationfront(ReservationEvenementRepository $reservationEvenementRepository): Response
    {
        dump($reservationEvenementRepository->findAll());
        return $this->render('reservation_evenement/reservationFront.html.twig', [
            'reservation_evenements' => $reservationEvenementRepository->findAll(),
        ]);

    }

    #[Route('/new', name: 'app_reservation_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationEvenementRepository $reservationEvenementRepository,EvenementRepository $evenementRepository): Response
    {
        $reservationEvenement = new ReservationEvenement();
        $form = $this->createForm(ReservationEvenementType::class, $reservationEvenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservationEvenementRepository->save($reservationEvenement, true);

            return $this->redirectToRoute('app_reservation_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_evenement/new.html.twig', [
            'reservation_evenement' => $reservationEvenement,
            'form' => $form,
            'evenements' => $evenementRepository->findAll(),

        ]);
    }

    #[Route('/{idReservationEvenement}', name: 'app_reservation_evenement_show', methods: ['GET'])]
    public function show(ReservationEvenement $reservationEvenement): Response
    {
        return $this->render('reservation_evenement/show.html.twig', [
            'reservation_evenement' => $reservationEvenement,
        ]);
    }

    #[Route('/{idReservationEvenement}/edit', name: 'app_reservation_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationEvenement $reservationEvenement, ReservationEvenementRepository $reservationEvenementRepository): Response
    {
        $form = $this->createForm(ReservationEvenementType::class, $reservationEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationEvenementRepository->save($reservationEvenement, true);

            return $this->redirectToRoute('app_reservation_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_evenement/edit.html.twig', [
            'reservation_evenement' => $reservationEvenement,
            'form' => $form,
        ]);
    }

    /*#[Route('/{idReservationEvenement}', name: 'app_reservation_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationEvenement $reservationEvenement, ReservationEvenementRepository $reservationEvenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationEvenement->getIdReservationEvenement(), $request->request->get('_token'))) {
            $reservationEvenementRepository->remove($reservationEvenement, true);
        }

        return $this->redirectToRoute('app_reservation_evenement_index', [], Response::HTTP_SEE_OTHER);
    }*/

    #[Route('/pdf/{id}'  , name: 'app_reservation_evenement_facture', methods: ['GET'])]

    public function factureReservation(Request $request,ReservationEvenementRepository $repository)
    {
        $id = $request->get("id");

        $reservationEvent= $repository->find(id:$id);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $dompdf->setHttpContext($contxt);


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reservation_evenement/pdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'reservation_events'=>$reservationEvent,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false

        ]);
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);






    }

}
