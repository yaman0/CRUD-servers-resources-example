<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
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
     * @Route("/category", name="category.index")
     */
    public function index()
    {
        $categories = $this->repository->findAll();
        return $this->render('category/index.html.twig', [
            "categories" => $categories,
            'controller_name' => 'CategoryController'
        ]);
    }

    /**
     * @Route("category/create", name="category.new")
     * @param Request $req
     * @return RedirectResponse|Response
     */
    public function new(Request $req)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Create Ok');
            $this->em->persist($category);
            $this->em->flush();
            return $this->redirectToRoute('category.index');
        }

        return $this->render('category/new.html.twig', [
            "category" => $category,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/category/{id}", name="category.edit", methods={"GET","POST"})
     * @param Category $category
     * @return Response
     */
    function edit(Request $req, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Edit Ok');
            $this->em->flush();
            return $this->redirectToRoute('category.index');
        }
        return $this->render('category/edit.html.twig', [
            "category" => $category,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/category/{id}", name="category.delete", methods={"DELETE"})
     * @param Category $category
     */
    function delete(Request $req, Category $category)
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $req->get('_token'))) {
            $this->addFlash('success', 'Delete Ok');
            $this->em->remove($category);
            $this->em->flush();
        }
        return $this->redirectToRoute('category.index');
    }

}
