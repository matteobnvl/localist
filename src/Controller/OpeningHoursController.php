<?php

namespace App\Controller;

use App\Entity\OpeningHours;
use App\Form\OpeningHoursType;
use App\Repository\OpeningHoursRepository;
use App\Repository\ShopKeeperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/opening/hours')]
class OpeningHoursController extends AbstractController
{
    #[Route('/', name: 'app_opening_hours_index', methods: ['GET'])]
    public function index(OpeningHoursRepository $openingHoursRepository): Response
    {
        return $this->render('opening_hours/index.html.twig', [
            'opening_hours' => $openingHoursRepository->findAll(),
        ]);
    }

    #[Route('/new/{shop_keeper}', name: 'app_opening_hours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $shop_keeper, ShopKeeperRepository $shopKeeperRepository, OpeningHoursRepository $openingHoursRepository): Response
    {
        $openingHour = new OpeningHours();
        $form = $this->createForm(OpeningHoursType::class, $openingHour);
        $form->handleRequest($request);

        $shopKeeper = $shopKeeperRepository->find($shop_keeper);

        $openingHours = $openingHoursRepository->findBy(['shopKeeper' => $shopKeeper]);


        $days = [];
        if (!empty($openingHours) && $form->isSubmitted()) {
            foreach ($openingHours as $openingDayHour) {
                array_push($days, $openingDayHour->getDay());
            }

            if (in_array($form->get('day')->getData(), $days)) {

                return $this->render('opening_hours/new.html.twig', [
                    'opening_hour' => $openingHour,
                    'form' => $form,
                    'shop_keeper' => $shopKeeper,
                    'message_error' => 'Jour déjà existant..',
                    'opening_hours' => $openingHours,
                    'edit' => false
                ]);
            }
        }


        if ($form->isSubmitted() && $form->isValid()) {

            $openingHour->setShopKeeper($shopKeeper);

            $entityManager->persist($openingHour);
            $entityManager->flush();

            return $this->redirectToRoute('app_opening_hours_new', ['shop_keeper' => $shop_keeper], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opening_hours/new.html.twig', [
            'opening_hour' => $openingHour,
            'form' => $form,
            'shop_keeper' => $shopKeeper,
            'message_error' => '',
            'opening_hours' => $openingHours,
            'edit' => false
        ]);
    }

    #[Route('/{id}', name: 'app_opening_hours_show', methods: ['GET'])]
    public function show(OpeningHours $openingHour): Response
    {
        return $this->render('opening_hours/show.html.twig', [
            'opening_hour' => $openingHour,
        ]);
    }

    #[Route('/{id}/edit/{shop_keeper}', name: 'app_opening_hours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OpeningHours $openingHour, EntityManagerInterface $entityManager, int $shop_keeper, OpeningHoursRepository $openingHoursRepository): Response
    {
        $form = $this->createForm(OpeningHoursType::class, $openingHour);
        $form->handleRequest($request);

        $openingHours = $openingHoursRepository->findBy(['shopKeeper' => $shop_keeper]);

        $days = [];
        if (!empty($openingHours) && $form->isSubmitted()) {
            foreach ($openingHours as $openingDayHour) {
                array_push($days, $openingDayHour->getDay());
            }

            if (in_array($form->get('day')->getData(), $days)) {

                return $this->render('opening_hours/edit.html.twig', [
                    'opening_hour' => $openingHour,
                    'form' => $form,
                    'shop_keeper_id' => $shop_keeper,
                    'edit' => true
                ]);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_opening_hours_new', ['shop_keeper' => $shop_keeper], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opening_hours/edit.html.twig', [
            'opening_hour' => $openingHour,
            'form' => $form,
            'edit' => true,
            'shop_keeper_id' => $shop_keeper,
        ]);
    }

    #[Route('/{id}', name: 'app_opening_hours_delete', methods: ['POST'])]
    public function delete(Request $request, OpeningHours $openingHour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$openingHour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($openingHour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_opening_hours_new', ['shop_keeper' => $request->query->get('shop_keeper')], Response::HTTP_SEE_OTHER);
    }
}
