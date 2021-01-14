<?php


namespace App\Controller;



use App\Entity\City;
use App\Form\FilterCitiesType;
use App\Form\FilterCategoryType;
use App\Form\FilterDepartmentsType;
use App\Repository\CityRepository;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\FarmerRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{

    public $listCities;
    /**
     * @Route("/", name="map")
     * @param FarmerRepository $farmerRepository
     * @param CityRepository $cityRepository ,
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param TransactionRepository $transactionRepository
     * @return Response
     */
    public function index(FarmerRepository $farmerRepository,
                          CityRepository $cityRepository,
                          Request $request,
                          CommentRepository $commentRepository,
                          TransactionRepository $transactionRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $cityFilter = $this->createForm(FilterCitiesType::class);
        $cityFilter->handleRequest($request);

        $departmentFilter = $this->createForm(FilterDepartmentsType::class);
        $departmentFilter->handleRequest($request);

        $cityByProduct = $this->createForm(FilterCategoryType::class);
        $cityByProduct->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Your comment has been sent with success !');

            $this->redirectToRoute('map');
        }

        if($departmentFilter->isSubmitted() && $departmentFilter->isValid()){
            $filter = $departmentFilter->getData()['Departement'];
            if($filter) {
                $farmers = $farmerRepository->findFarmersInDepartment($filter);
            }
        }

        if($cityFilter->isSubmitted() && $cityFilter->isValid()){
            $filter = $cityFilter->getData()['Ville'];
            if($filter) {
                $farmers = $farmerRepository->findFarmersInCity($filter);
            }
        }

        if($cityByProduct->isSubmitted() && $cityByProduct->isValid()){
            $category = $cityByProduct->getData()['category'];
//            $farmers = $farmerRepository->findFarmersByCategory($category);
        }

        return $this->render('map/map_index.html.twig', [
            'formComment' => $form->createView(),
            'formCity' => $cityFilter->createView(),
            'formDepartment' => $departmentFilter->createView(),
            'cities3' => $cityRepository->findBy([], [], 3),
            'formByProduct' => $cityByProduct->createView(),
            'farmers' => $farmers ?? $farmerRepository->findBy([], [], 10),
            'cities' => $cityRepository->findCitiesWithFarmers(),
            'comments' => $commentRepository->findAll(),
        ]);
    }
}
