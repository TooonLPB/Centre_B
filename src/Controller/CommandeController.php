<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Plat;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, Security $security): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/{menuId}/create', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function createOrderFromMenu(Request $request, $menuId, EntityManagerInterface $entityManager, Security $security): Response
    {
        $commande = new Commande();
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_home');
        }
        $commande->setUserID($user);
        $commande->setDateAndTimeOfOrder(new \DateTime());
        $menu = $entityManager->getRepository(Menu::class)->find($menuId);
        if (!$menu) {
            throw $this->createNotFoundException('Le menu demandé n\'existe pas.');
        }
        $commande->setMenuId($menu);
        $starterIds = $menu->getEntree();
        $maincourseIds = [$menu->getPlatUn(), $menu->getPlatDeux(),$menu->getPlatTrois()];
        $dessertIds = $menu->getDessert();
        $form = $this->createForm(CommandeType::class, $commande, [
         
            'maincourseIds' => $maincourseIds,
    
            
        ]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
    
               if( $form->get('Type')->getData()->getRegime() == "Viande"){
                $menu->setQuantitéViande($menu->getQuantitéViande()+1);
               }
               elseif( $form->get('Type')->getData()->getRegime() == "Poisson"){
                $menu->setQuantitépoisson($menu->getQuantitépoisson()+1);

               }
               elseif( $form->get('Type')->getData()->getRegime() == "Végétarien"){
                $menu->setQuantitévegi($menu->getQuantitévegi()+1);

               }

            $entityManager->persist($commande);
            $entityManager->flush();
            return $this->redirectToRoute('home', ['id' => $commande->getUserID()->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('commande/new.html.twig', [
            'form' => $form->createView(),
            'dessertIds' => $dessertIds,
            'starterIds' => $starterIds,
        ]);

    }

    #[Route('/{id}/index', name: 'app_commande_indexof', methods: ['GET', 'POST'])]
    public function indexofUser(CommandeRepository $commandeRepository, EntityManagerInterface $entityManager,Security $security,$id): Response
    {
        $user = $security->getUser();
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/');
        }
        $commande = new Commande();
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_home');
        }
        $users = $entityManager->getRepository(User::class)->find($id);

        $commande = $commandeRepository->findBy(['UserId' => $id]);

        return $this->render('user/showHistorique.html.twig', [ 
            'commandes' => $commande,
            'user'=>$users
        ]);
    }


    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande, Security $security): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,$id, Commande $commande, EntityManagerInterface $entityManager, Security $security): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);
        $commande->getMenuId()->getId();
        $menu = $entityManager->getRepository(Menu::class)->find($commande->getMenuId()->getId());
        if (!$menu) {
            throw $this->createNotFoundException('Le menu demandé n\'existe pas.');
        }
        $maincourseIds = [$menu->getPlatUn(), $menu->getPlatDeux(),$menu->getPlatTrois()];
        $form = $this->createForm(CommandeType::class, $commande,[
            'maincourseIds' => $maincourseIds,
        ]);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse('/');
        }
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
