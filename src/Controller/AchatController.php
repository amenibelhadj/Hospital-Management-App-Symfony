<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\nameSearch;
use App\Entity\PriceSearch;
use App\Entity\Produit;
use App\Form\AchatType;
use App\Form\NameSearchType;
use App\Form\PriceSearchType;
use App\Form\ProduitType;
use App\Repository\AchatRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

#[Route('/achat')]


class AchatController extends AbstractController
{
    
    
    #[Route('/', name: 'app_achat_index', methods: ['GET', 'POST'])]
    public function index(AchatRepository $achatRepository,ProduitRepository $produitRepository,Request $request): Response
    {
    $nameSearch = new nameSearch();
    $form = $this->createForm(NameSearchType::class,$nameSearch);
    $form->handleRequest($request);
   //initialement le tableau des articles est vide, 
   //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
   // $articles= [];
    $articles =$produitRepository->findAll();
    
   if($form->isSubmitted() && $form->isValid()) {
   //on récupère le nom d'article tapé dans le formulaire
    $nom = $nameSearch->getNom();   
    if ($nom!="") 
      //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
      $articles= $this->getDoctrine()->getRepository(Produit::class)->findBy(['nom' => $nom] );
    else   
      //si si aucun nom n'est fourni on affiche tous les articles
      $articles= $this->getDoctrine()->getRepository(Produit::class)->findAll();
       
   }
    return  $this->render('achat/index.html.twig',[ 'form' =>$form->createView(), 'produits' => $articles]); 
    }


    
    #[Route('/art_prix', name: 'article_par_prix', methods: ['GET','POST'])]
    public function articlesParPrix(Request $request)
    {
     
      $priceSearch = new PriceSearch();
      $form = $this->createForm(PriceSearchType::class,$priceSearch);
      $form->handleRequest($request);

     $articles= [];
    //$articles =$produitRepository->findAll();

      if($form->isSubmitted() && $form->isValid()) {
        $minPrice = $priceSearch->getMinPrice(); 
        $maxPrice = $priceSearch->getMaxPrice();
          
        $articles= $this->getDoctrine()->getRepository(Produit::class)->findByPriceRange($minPrice,$maxPrice);
    }

    return  $this->render('achat/articlesParPrix.html.twig',[ 'form' =>$form->createView(), 'produits' => $articles]);  
  }
    
    

    #[Route('/panier', name: 'app_panier', methods: ['GET'])]
  public function indexPanier(AchatRepository $achatRepository,SessionInterface $session, ProduitRepository $productsRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $productsRepository->find($id);
            $dataPanier[] = [
                
                "produits" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPrix() * $quantite;
        }
        

        return $this->render('achat/pan.html.twig', compact("dataPanier", "total"));
    }

    #[Route('/addPanier/{id}', name: 'app_add_panier', methods: ['GET', 'POST'])]
    public function addPanier(ManagerRegistry $doctrine,Produit $product, SessionInterface $session, NotifierInterface $notifier)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();
        $em = $doctrine->getManager();
       
       
       $product->setQuantite($product->getQuantite($id)-1);
       $em->persist($product);
       $em->flush();
        if(!empty($panier[$id])){
            $panier[$id]++;
        
        }else{
            $panier[$id] = 1;
      
        }
        
        ///////////////////////////
        /*
        $numleft  = $product->getQuantite($id); 
        if($numleft==0) {
           // out of stock
            echo "There are no items available at this time."; 
        }
        else if($numleft==1) {
            echo "Only ".$numleft ." item left.";
        }
        else {
            echo "Only ".$numleft ." items left.";
        }*/
       
        // On sauvegarde dans la session
        $session->set("panier", $panier);
       // $product->setQuantite($product->getQuantite($id)-1);

        return $this->redirectToRoute("app_panier");


        
        
    }
/*
    #[Route('/{id}', name: 'app_achat_show', methods: ['GET'])]
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }
*/
/*
    #[Route('/{id}/edit', name: 'app_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Achat $achat, AchatRepository $achatRepository): Response
    {
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatRepository->save($achat, true);

            return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat/edit.html.twig', [
            'achat' => $achat,
            'form' => $form,
        ]);
    } */
/*
    #[Route('/{id}', name: 'app_achat_delete', methods: ['POST'])]
    public function delete(Request $request, Achat $achat, AchatRepository $achatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achat->getId(), $request->request->get('_token'))) {
            $achatRepository->remove($achat, true);
        }

        return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
    }
    */

    #[Route('/{id}/remove', name: 'app_panier_remove', methods: ['GET','POST'])]
    public function remove(ManagerRegistry $doctrine,Produit $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();
        $em = $doctrine->getManager();
        $product->setQuantite($product->getQuantite($id)+1);
        $em->persist($product);
       $em->flush();
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_panier");
    }

    #[Route('/{id}/delete', name: 'app_panier_delete', methods: ['GET','POST'])]
    public function delete(ManagerRegistry $doctrine,Produit $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();
        $em = $doctrine->getManager();
        $product->setQuantite($product->getQuantite($id)+1);
        $em->persist($product);
       $em->flush();
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_panier");
    }

    #[Route('/delete', name: 'panier_delete_all', methods: ['GET','POST'])]

    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("app_panier");
    }


    #[Route('/print', name: 'app_achat_print', methods: ['GET'])]
    public function print(AchatRepository $achatRepository,SessionInterface $session, ProduitRepository $productsRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $productsRepository->find($id);
            $dataPanier[] = [
                
                "produits" => $product,
                "quantite" => $quantite,
                "total" => $total += $product->getPrix() * $quantite
            ];
            
        }
       
        $pdfOptions= new Options();
        $pdfOptions-> set ('defaultFont','Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf =new Dompdf($pdfOptions);
        $achat=$dataPanier;
       
        $html =$this->renderView('achat/print.html.twig', [
            'achat' => $achat,
            
        ]);
        $dompdf->loadHtml($html); 
        $dompdf->setPaper('A4,landscape');
        $dompdf->render();
        $dompdf->stream("mypdf.pdf",["Attachment"=>true]);
        $dompdf->stream("devis.pdf",["Attachment"=>false]);



    }


   
    

    
}
