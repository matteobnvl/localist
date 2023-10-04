<?php

namespace App\Controller;

use App\Repository\ShopKeeperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ShopKeeperRepository $shopKeeperRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'shop_keepers' => $shopKeeperRepository->findAll()
        ]);
    }
}
