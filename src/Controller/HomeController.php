<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Menu;
use App\Repository\MenuRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Security $security,MenuRepository $ordersRepository): Response
    {
        $menu = new Menu;
        $user = $security->getUser();
        $menu = $ordersRepository->findAll();
        if ($user == null) {
            return new RedirectResponse('/login');
        }
        return $this->render('home/index.html.twig', ['controller_name' => 'HomeController','menu'=>$menu]);
    }
}