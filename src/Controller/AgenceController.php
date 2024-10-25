<?php
namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/agence')]
class AgenceController extends AbstractController
{
    #[Route('/', name: 'agence_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $agences = $em->getRepository(Agence::class)->findAll();
        return $this->render('agence/index.html.twig', ['agences' => $agences]);
    }

    #[Route('/new', name: 'agence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($agence);
            $em->flush();
            return $this->redirectToRoute('agence_index');
        }

        return $this->render('agence/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}', name: 'agence_show', methods: ['GET'])]
    public function show(Agence $agence): Response
    {
        return $this->render('agence/show.html.twig', ['agence' => $agence]);
    }

    #[Route('/{id}/edit', name: 'agence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agence $agence, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('agence_index');
        }

        return $this->render('agence/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}', name: 'agence_delete', methods: ['POST'])]
    public function delete(Request $request, Agence $agence, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agence->getId(), $request->request->get('_token'))) {
            $em->remove($agence);
            $em->flush();
        }

        return $this->redirectToRoute('agence_index');
    }
}
