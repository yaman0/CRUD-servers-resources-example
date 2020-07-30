<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)
            ->findAll();
        return $this->render('category/index.html.twig', [
            "categories" => $categories,
            'controller_name' => 'CategoryController'
        ]);
    }

    /**
     * @Route("/category/{id}", name="category_show")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(int $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)
            ->find($id);
        return $this->render('category/show.html.twig', [
            "category" => $category
        ]);
    }
}
