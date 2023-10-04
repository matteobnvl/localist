<?php

namespace App\Controller;

use App\Entity\ShopKeeper;
use App\Form\ShopKeeperType;
use App\Repository\ShopKeeperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shop-keeper')]
class ShopKeeperController extends AbstractController
{
    #[Route('/new', name: 'app_shop_keeper_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shopKeeper = new ShopKeeper();
        $form = $this->createForm(ShopKeeperType::class, $shopKeeper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $shopKeeper->setManager($this->getUser());

            $entityManager->persist($shopKeeper);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop_keeper/new.html.twig', [
            'shop_keeper' => $shopKeeper,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_keeper_show', methods: ['GET'])]
    public function show(ShopKeeper $shopKeeper): Response
    {
        return $this->render('shop_keeper/show.html.twig', [
            'shop_keeper' => $shopKeeper,
            'posts' => $shopKeeper->getPosts()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shop_keeper_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ShopKeeper $shopKeeper, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShopKeeperType::class, $shopKeeper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop_keeper/edit.html.twig', [
            'shop_keeper' => $shopKeeper,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_keeper_delete', methods: ['POST'])]
    public function delete(Request $request, ShopKeeper $shopKeeper, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopKeeper->getId(), $request->request->get('_token'))) {
            $entityManager->remove($shopKeeper);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
