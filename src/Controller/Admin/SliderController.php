<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sliders')]
class SliderController extends AbstractController
{
    #[Route('/', name: 'admin_slider_index', methods: ['GET'])]
    public function index(SliderRepository $sliderRepository): Response
    {
        return $this->render('admin/slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_slider_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slider);
            $entityManager->flush();

            return $this->redirectToRoute('admin_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_slider_show', methods: ['GET'])]
    public function show(Slider $slider): Response
    {
        return $this->render('admin/slider/show.html.twig', [
            'slider' => $slider,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_slider_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slider $slider): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_slider_delete', methods: ['POST'])]
    public function delete(Request $request, Slider $slider): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($slider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
