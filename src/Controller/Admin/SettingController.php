<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use App\Form\SettingType;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/setting')]
class SettingController extends AbstractController
{
    #[Route('/', name: 'admin_setting_index', methods: ['GET'])]
    public function index(SettingRepository $repository): Response
    {
        $setting = $repository->createQueryBuilder('s')->setMaxResults(1)->getQuery()->getOneOrNullResult();
        if($setting == null)
        {
            $setting = new Setting();
        }
        return $this->render('admin/setting/show.html.twig', [
            'setting' => $setting,
        ]);
    }

    #[Route('/edit', name: 'admin_setting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SettingRepository $repository): Response
    {
        $setting = $repository->createQueryBuilder('s')->setMaxResults(1)->getQuery()->getOneOrNullResult();

        $wasNull = false;
        if($setting == null){
            $setting = new Setting();
            $wasNull = true;
        }
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($wasNull){
                $this->getDoctrine()->getManager()->persist($setting);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/setting/edit.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }
}
