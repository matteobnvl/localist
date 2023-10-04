<?php

namespace App\Controller;

use App\Repository\ShopKeeperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ShopKeeperRepository $shopKeeperRepository): Response
    {


        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $this->getUser(),
            'shop_keepers' =>$this->getUser()->getShopKeepers()
        ]);
    }
}
