<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Entity\ShopKeeper;
use App\Form\NoticeType;
use App\Repository\NoticeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notice')]
class NoticeController extends AbstractController
{
    #[Route('/', name: 'app_notice_index', methods: ['GET'])]
    public function index(NoticeRepository $noticeRepository): Response
    {
        return $this->render('notice/index.html.twig', [
            'notices' => $noticeRepository->findAll(),
        ]);
    }

    #[Route('/new/{shop_keeper}', name: 'app_notice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $shop_keeper): Response
    {
        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);
        $shopKeeper = $entityManager->getRepository(ShopKeeper::class)->find($shop_keeper);

        if ($form->isSubmitted() && $form->isValid()) {

            $notice->setShopKeeper($shopKeeper);
            $notice->setPostedAt((new DateTimeImmutable()));
            
            $entityManager->persist($notice);
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_keeper_show', ['id' => $shopKeeper->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notice/new.html.twig', [
            'notice' => $notice,
            'form' => $form,
            'shop_keeper' => $shopKeeper
        ]);
    }

    #[Route('/{id}', name: 'app_notice_show', methods: ['GET'])]
    public function show(Notice $notice): Response
    {
        return $this->render('notice/show.html.twig', [
            'notice' => $notice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notice $notice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notice/edit.html.twig', [
            'notice' => $notice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notice_delete', methods: ['POST'])]
    public function delete(Request $request, Notice $notice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notice_index', [], Response::HTTP_SEE_OTHER);
    }
}
