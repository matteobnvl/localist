<?php

namespace App\Controller;

use App\Repository\ShopKeeperRepository;
use App\Form\Filter\ShopKeeperFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ShopKeeperRepository $shopKeeperRepository): Response
    {
        $form = $this->createForm(ShopKeeperFilterType::class);
        $form->handleRequest($request);

        $filters = $form->isSubmitted() ? $form->getData() : [];
        $commerces = $shopKeeperRepository->getFiltered($filters);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'shop_keepers' => $commerces,
            'form' => $form->createView()
        ]);
    }
}
