<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class BookController extends AbstractController
{
    public function bookList(): object
    {
        $entityManager = $this->getDoctrine()->getManager();

        $books = $entityManager->getRepository(Book::class)->findAll();

        $data = [];
 
        foreach ($books as $book) {
           $data[] = [
               'id' => $book->getId(),
               'title' => $book->getTitle()
           ];
        }
 
        return $this->json($data);

    }

    public function createBook(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $bodyParam = json_decode($request->getContent(), false);

        $Book = new Book();
        $Book->setTitle($bodyParam->title);

        // tell Doctrine you want to (eventually) save the Book (no queries yet)
        $entityManager->persist($Book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['id'=>$Book->getId()]);

        $data =[
               'id' => $book->getId(),
               'title' => $book->getTitle()
           ];
        return $this->json($data);
    }
    public function updateBook(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $bookID =  $request->attributes->get('id');
        $bodyParam = json_decode($request->getContent(), false);
        $book = $entityManager->getRepository(Book::class)->findOneBy(['id'=>$bookID]);
        $book->setTitle($bodyParam->title);

        // tell Doctrine you want to (eventually) save the Book (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
  

        $data =[
               'id' => $book->getId(),
               'title' => $book->getTitle()
           ];
        return $this->json($data);
    }
    public function deleteBook(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $bookID =  $request->attributes->get('id');
        $book = $entityManager->getRepository(Book::class)->findOneBy(['id'=>$bookID]);
        $entityManager->remove($book);
        $entityManager->flush();
        $data =[
            'MSG' => $bookID." book has been successfully deleted !."
        ];
        return $this->json($data);
    }
}
