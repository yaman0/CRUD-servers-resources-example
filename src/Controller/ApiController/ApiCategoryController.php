<?php


namespace App\Controller\ApiController;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiCategoryController
 * @package App\Controller\ApiController
 * @Route("/api/category")
 */
class ApiCategoryController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(CategoryRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="api.category.index", methods={"GET"})
     */
    public function index()
    {
        $categories = $this->repository->findAll();
        return $this->json($categories);

    }

    /**
     * @Route("", name="api.category.new", methods={"POST"})
     * @param Request $req
     * @param SerializerInterface $serializer
     * @return RedirectResponse|Response
     */
    public function new(Request $req, SerializerInterface $serializer)
    {
        /** @var Category $category */
        $category = $serializer->deserialize($req->getContent(), Category::class, 'json');
        $this->em->persist($category);
        $this->em->flush();
        return $this->json($category);
    }

    /**
     * @Route("/{id}", name="api.category.show", methods="GET")
     * @param Category $category
     * @return Response
     */
    function show(Category $category)
    {
        return $this->json($category);
    }

    /**
     * @Route("/{id}", name="api.category.edit", methods={"PUT"})
     * @param Request $req
     * @param Category $category
     * @param SerializerInterface $serializer
     * @return Response
     */
    function edit(Request $req, Category $category, SerializerInterface $serializer)
    {
        /** @var Category $edited_category */
        $edited_category = $serializer->deserialize($req->getContent(), Category::class, 'json');
        $category->setName($edited_category->getName());
        $category->setColor($edited_category->getColor());
        $this->em->flush();
        return $this->json($category);
    }

    /**
     * @Route("/{id}", name="api.category.delete", methods={"DELETE"})
     * @param Category $category
     */
    function delete(Category $category)
    {
        $this->em->remove($category);
        $this->em->flush();
        return $this->json($category);
    }
}