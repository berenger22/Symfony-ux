<?php

namespace App\Controller;

use Symfony\UX\Cropperjs\Form\CropperType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CropperController extends AbstractController
{
    #[Route('/cropper', name: 'app_cropper')]
    public function index(CropperInterface $cropper, Request $request): Response
    {
        $crop = $cropper->createCrop('/images/suisse.jpg');
        $crop->setCroppedMaxSize(2000, 1500);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => '/images/suisse.jpg',
                'cropper_options' => [
                    'aspectRatio' => 2000 / 1500,
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the cropped image data (as a string)
            $crop->getCroppedImage();

            // Create a thumbnail of the cropped image (as a string)
            $crop->getCroppedThumbnail(200, 150);

            // ...
        }

        return $this->render('cropper/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
