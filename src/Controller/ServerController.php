<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Server;
use App\Form\CategoryType;
use App\Form\ServerType;
use App\Repository\ServerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServerController
 * @package App\Controller
 * @Route("/server")
 */
class ServerController extends AbstractController
{
    /**
     * @var ServerRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ServerRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("", name="server.index")
     */
    public function index()
    {
        $servers = $this->repository->findAll();
        return $this->render('server/index.html.twig', [
            "servers" => $servers,
        ]);
    }


    /**
     * @Route("/create", name="server.new")
     * @param Request $req
     * @return RedirectResponse|Response
     */
    public function new(Request $req)
    {
        $category = new Server();
        $form = $this->createForm(ServerType::class, $category);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Create Ok');
            $this->em->persist($category);
            $this->em->flush();
            return $this->redirectToRoute('server.index');
        }

        return $this->render('server/new.html.twig', [
            "server" => $category,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="server.edit", methods={"GET","POST"})
     * @param Server $server
     * @return Response
     */
    function edit(Request $req, Server $server)
    {
        $form = $this->createForm(ServerType::class, $server);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Edit Ok');
            $this->em->flush();
            return $this->redirectToRoute('server.index');
        }
        return $this->render('server/edit.html.twig', [
            "server" => $server,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="server.delete", methods={"DELETE"})
     * @param Server $server
     */
    function delete(Request $req, Server $server)
    {
        if ($this->isCsrfTokenValid('delete' . $server->getId(), $req->get('_token'))) {
            $this->addFlash('success', 'Delete Ok');
            $this->em->remove($server);
            $this->em->flush();
        }
        return $this->redirectToRoute('server.index');
    }
}
