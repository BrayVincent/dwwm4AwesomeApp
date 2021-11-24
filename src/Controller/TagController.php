<?php

namespace App\Controller;

use Exception;
use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tag")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/listing", name="tag_index", methods={"GET"})
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tag_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($tag);
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'danger',
                    'Le catégorie existe déjà'
                );
                return $this->redirectToRoute('tag_new', [], Response::HTTP_SEE_OTHER);

                $this->addFlash(
                    'success',
                    'Le catégorie a été ajoutée'
                );
            }

            return $this->redirectToRoute('tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tag_show", methods={"GET"})
     */
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    /**
     * @Route("/update/{id}", name="tag_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'danger',
                    'Le catégorie existe déjà'
                );
                return $this->redirectToRoute('tag_edit', ['id' => $tag->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash(
                'success',
                'Le catégorie a été modifié'
            );

            return $this->redirectToRoute('tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tag_delete", methods={"POST"})
     */
    public function delete(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($tag);
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash(
                    'danger',
                    'Suppression impossible'
                );
                return $this->redirectToRoute('tag_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        $this->addFlash(
            'success',
            'Suppression réussie'
        );
        return $this->redirectToRoute('tag_index', [], Response::HTTP_SEE_OTHER);
    }
}
