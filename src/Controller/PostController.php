<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\ShopKeeper;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\FileUploader;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/new/{shop_keeper}', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,int $shop_keeper, FileUploader $fileUploader): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $shopKeeper = $entityManager->getRepository(ShopKeeper::class)->find($shop_keeper);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $filename */
            $file = $form->get('url_image')->getData();

            if ($file) {
                $filename = $fileUploader->upload($file);
                $post->setUrlImage($filename);
            }

            $post->setPostedBy($shopKeeper);
            $post->setCreatedAt((new DateTimeImmutable()));

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_keeper_show', ['id' => $shopKeeper->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
            'shop_keeper' => $shopKeeper,
            'isEdit' => true
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit/{shop_keeper}', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager,int $shop_keeper, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $filenamePost = $post->getUrlImage();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('url_image')->getData() !== null) {
                /** @var UploadedFile $filename */
                $file = $form->get('url_image')->getData();

                if ($file) {
                    $filename = $fileUploader->upload($file);
                    $post->setUrlImage($filename);
                }
            } else {
                $post->setUrlImage($filenamePost);
            }
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_keeper_show', ['id' => $shop_keeper], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
            'shop_keeper' => $post->getPostedBy(),
            'isEdit' => true
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shop_keeper_show', ['id' => $post->getPostedBy()->getId()], Response::HTTP_SEE_OTHER);
    }
}
