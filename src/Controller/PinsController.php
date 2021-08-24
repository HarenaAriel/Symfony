<?php

namespace App\Controller;

use App\Entity\Pin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{

    /**
     * @Route("/", name="pins")
     */
    public function index(EntityManagerInterface $em): Response //public function index(PinRepository $repos): Response
    {
        $repo = $em->getRepository(Pin::class);
        $pins = $repo->findAll();

        return $this->render('pins/index.html.twig', ['pins' => $pins]); 
        //return $this->render('pins/index.html.twig', compact('pins'));
        //return $this->render('pins/index.html.twig', ['pins' => $repo->findAll()]); 
    }

    /**
     * @Route("/pins/create")
     */
    public function create(): Response
    {
        return $this->render('pins/create.html.twig');
    }
}
