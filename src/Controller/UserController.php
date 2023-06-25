<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    

    #[Route('/admin', name: 'app_admin_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/indexAdmin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/utilisateur', name: 'app_user_index', methods: ['GET'])]
    public function index2(UserRepository $userRepository): Response
    {
        return $this->render('user/indexUser.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/patient', name: 'app_patient_index', methods: ['GET'])]
    public function index3(UserRepository $userRepository): Response
    {
        return $this->render('user/indexPatient.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    #[Route('/medecin', name: 'app_medecin_index', methods: ['GET'])]
    public function index4(UserRepository $userRepository): Response
    {
        return $this->render('user/indexMedecin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/infirmier', name: 'app_infirmier_index', methods: ['GET'])]
    public function index5(UserRepository $userRepository): Response
    {
        return $this->render('user/indexInfirmier.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/agent', name: 'app_agent_index', methods: ['GET'])]
    public function index6(UserRepository $userRepository): Response
    {
        return $this->render('user/indexAgent.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/pharmacien', name: 'app_pharmacien_index', methods: ['GET'])]
    public function index7(UserRepository $userRepository): Response
    {
        return $this->render('user/indexPharmacien.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
