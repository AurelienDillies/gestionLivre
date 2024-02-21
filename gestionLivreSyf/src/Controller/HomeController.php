<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('home/index.html.twig', [
            'allbook' => $books,
        ]);
    }

    #[Route('/resumer/{id}', name: 'app_resumer')]
    public function resumer($id, BookRepository $repository): Response
    {
        $book = $repository->find($id);
        return $this->render('home/res.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/create', name: 'app_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/crea.html.twig', ['form' => $form->createView()]);
    }
}
