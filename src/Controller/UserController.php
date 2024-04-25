<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType; 
use App\Form\UserTypeEdit; 
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/');
        }
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, Security $security): Response
    {
        $user = $security->getUser();
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $createdAt = new \DatetimeImmutable('now');

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt($createdAt);
            $user->setRoles([$form->get('roles')->getData()]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, Security $security): Response
    {
        $user = $security->getUser();
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/');
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, Security $security, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $currentUser = $security->getUser();
    
        // Vérifie si l'utilisateur connecté est un administrateur
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Si l'utilisateur connecté n'est pas un administrateur, vérifie s'il essaie de modifier son propre compte
            if ($currentUser !== $user) {
                // Si l'utilisateur connecté essaie de modifier un autre compte que le sien, redirige-le vers la page d'accueil ou une page d'erreur
                return new RedirectResponse('/');
            }
        }
    
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie si un nouveau mot de passe est fourni
            $newPassword = $form->get('password')->getData();
            if (!empty($newPassword)) {
                // Met à jour le mot de passe seulement si un nouveau mot de passe est fourni
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $newPassword
                    )
                );
            }
    
            // Seul un administrateur peut modifier les rôles d'un utilisateur
            if ($this->isGranted('ROLE_ADMIN')) {
                $user->setRoles([$form->get('roles')->getData()]);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $userToDelete, EntityManagerInterface $entityManager, Security $security): Response
    {
        $currentUser = $security->getUser();
        if (!$this->isGranted('ROLE_ADMIN', $currentUser)) {
            // Redirection si l'utilisateur actuel n'a pas le rôle ADMIN
            return new RedirectResponse('/');
        }
        
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $userToDelete->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userToDelete); // Suppression de l'utilisateur
            $entityManager->flush(); // Enregistrement des changements dans la base de données
        }

        // Redirection vers la liste des utilisateurs après la suppression
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
