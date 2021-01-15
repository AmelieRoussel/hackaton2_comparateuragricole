<?php


namespace App\Controller;


use App\DataFixtures\ProductFixtures;
use App\Entity\City;
use App\Entity\Farmer;
use App\Form\FilterCitiesType;
use App\Form\FilterCategoryType;
use App\Form\FilterDepartmentsType;
use App\Repository\BuyerRepository;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\FarmerRepository;
use App\Repository\ProductRepository;
use App\Repository\TransactionRepository;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /**
     * @Route("/", name="map")
     * @param FarmerRepository $farmerRepository
     * @param CityRepository $cityRepository ,
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param Slugify $slugify
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param BuyerRepository $buyerRepository
     * @param TransactionRepository $transactionRepository
     * @return Response
     */
    public function index(FarmerRepository $farmerRepository,
                          CityRepository $cityRepository,
                          Request $request,
                          CommentRepository $commentRepository,
                          Slugify $slugify,
                          ProductRepository $productRepository,
                          CategoryRepository $categoryRepository,
                          BuyerRepository $buyerRepository,
                          TransactionRepository $transactionRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $departmentFilter = $this->createForm(FilterDepartmentsType::class);
        $departmentFilter->handleRequest($request);

        $cityFilter = $this->createForm(FilterCitiesType::class);
        $cityFilter->handleRequest($request);

        $cityByProduct = $this->createForm(FilterCategoryType::class);
        $cityByProduct->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('primary', 'Votre commentaire a bien été enregistré !');

            return $this->redirectToRoute('map');
        }

        if ($departmentFilter->isSubmitted() && $departmentFilter->isValid()) {
            $filter = $departmentFilter->getData()['Departement'];
            if ($filter) {
                $farmers = $farmerRepository->findFarmersInDepartment($filter);
                $cities = $cityRepository->findBy(['department' => $filter]);
            }
        }

        if ($cityFilter->isSubmitted() && $cityFilter->isValid()) {
            $filter = $cityFilter->getData()['Ville'];
            $filter = $slugify->generate($filter);
            if ($filter) {
                $farmers = $farmerRepository->findFarmersInCity($filter);
                $cities = $cityRepository->findOneBy(['slug' => $filter]);
            }
        }

        if ($cityByProduct->isSubmitted() && $cityByProduct->isValid()) {
            $category = $cityByProduct->getData()['category'];
            if ($category) {
                $farmers = $transactionRepository->findFarmersByProduct($category);
            }
        }

        return $this->render('map/map_index.html.twig', [
            'formComment' => $form->createView(),
            'formCity' => $cityFilter->createView(),
            'formDepartment' => $departmentFilter->createView(),
            'cities3' => $cityRepository->findBy([], [], 3),
            'formByProduct' => $cityByProduct->createView(),
            'farmers' => $farmers ?? $farmerRepository->findFarmersWithData(),
            'cities' => $cities ?? $cityRepository->findCitiesWithFarmers(),
            'comments' => $commentRepository->findAll(),
            'buyers' => $buyerRepository->findAll(),
            'farmer' => $farmerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param Farmer $farmer
     * @return Response
     */
    public function writeComment(EntityManagerInterface $entityManager, Request $request, Farmer $farmer): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setAuthor($this->getUser());
            $comment->setFarmer($farmer);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->redirectToRoute('map');
        }
        $this->render('map/map_index.html.twig', [
            'formComment' => $form->createView(),
        ]);
    }

    /**
     * @Route("/about-us", name="about_us")
     * @return Response
     */
    public function aboutUs(): Response
    {
        return $this->render('about_us.html.twig');
    }
}
