<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\RdvRepository;

class MainController extends AbstractController
{
   /**
     * @Route("/c", name="main")
     */
    public function index(RdvRepository $calendar)
    {
        $events = $calendar->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
    
                'date' => $event->getDate(),

                'nom_med' => $event->getNomMed(),
                
                'heure' => $event->getHeure(),
             
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('main/index.html.twig', compact('data'));
    }
}
