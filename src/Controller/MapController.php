<?php


namespace App\Controller;


use App\Repository\FarmerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/", name="map")
     * @param FarmerRepository $farmerRepository
     * @return Response
     */
    public function index(FarmerRepository $farmerRepository): Response
    {
        return $this->render('map/map_index.html.twig', [
            'farmers' => $farmerRepository->findBy([], [], 10)
        ]);
    }
}
