<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{

    /**
     * @Route("/", name="pins")
     */
    public function index(EntityManagerInterface $em): Response // ublic function index(PinRepository $repos): Response
    {
        $repo = $em->getRepository(Pin::class);
        $pins = $repo->findAll();

        return $this->render('pins/index.html.twig', ['pins' => $pins]); 
        //return $this->render('pins/index.html.twig', compact('pins'));
        //return $this->render('pins/index.html.twig', ['pins' => $repo->findAll()]); 
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="show_pin")
     */
    public function show(EntityManagerInterface $em, int $id): Response // public function show(PinRepository $repos): Response
    {
        $repo = $em->getRepository(Pin::class);
        $pin = $repo->find($id);

        if($pin == null){
            throw $this->createNotFoundException();
        }

        return $this->render('pins/show.html.twig', ['pin' => $pin]);
        
        //return $this->render('pins/index.html.twig', ['pins' => $repo->find(1)]); 
    }

    /**
     * @Route("/pins/create", name="create_pins", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;

        $form = $this->createFormBuilder($pin)
                ->add('title', TextType::class, ['attr' => [
                    'class' => 'container'
                ]])
                ->add('description', TextareaType::class, ['attr' => [
                    'rows' => '5',
                    'cols' => '60'
                ]])
                ->getForm()
        ;

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('show_pin', ['id' => $pin->getId()]);

        }

        return $this->render("pins/create.html.twig", [
            'creationForm' => $form->createView(),
            'pin' => $pin
        ]);

    }

    /**
     * @Route("/pin/{id<[0-9]+>}/delete", name="delete_pin")
     */
    public function delete(Pin $pin): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pin);
        $em->flush();

        return $this->redirectToRoute('pins'); 
    }

    /**
     * @Route("/pin/{id<[0-9]+>}/edit", name="edit_pin", methods={"GET", "POST"})
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($pin)
                ->add('title', TextType::class, ['attr' => [
                    'class' => 'container'
                ]])
                ->add('description', TextareaType::class, ['attr' => [
                    'rows' => '5',
                    'cols' => '60'
                ]])
                ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('show_pin', ['id' => $pin->getId()]);

        }

        return $this->render("pins/create.html.twig", [
            'creationForm' => $form->createView(),
            'pin' => $pin
        ]);
    }
}
