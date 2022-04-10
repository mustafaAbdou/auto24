<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class AuthorController extends AbstractController
{
    public function authorList(): object
    {
        $entityManager = $this->getDoctrine()->getManager();

        $Authors = $entityManager->getRepository(Author::class)->findAll();

        $data = [];
 
        foreach ($Authors as $Author) {
           $data[] = [
               'id' => $Author->getId(),
               'Name' => $Author->getName()
           ];
        }
 
        return $this->json($data);

    }

    public function createAuthor(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $bodyParam = json_decode($request->getContent(), false);

        $Author = new Author();
        $Author->setName($bodyParam->name);

        // tell Doctrine you want to (eventually) save the Author (no queries yet)
        $entityManager->persist($Author);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        $Author = $entityManager->getRepository(Author::class)->findOneBy(['id'=>$Author->getId()]);

        $data =[
               'id' => $Author->getId(),
               'Name' => $Author->getName()
           ];
        return $this->json($data);
    }

    public function updateAuthor(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $authorID =  $request->attributes->get('id');
        $bodyParam = json_decode($request->getContent(), false);
        $author = $entityManager->getRepository(Author::class)->findOneBy(['id'=>$authorID]);
        $author->setTitle($bodyParam->title);

        // tell Doctrine you want to (eventually) save the author (no queries yet)
        $entityManager->persist($author);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
  

        $data =[
               'id' => $author->getId(),
               'title' => $author->getTitle()
           ];
        return $this->json($data);
    }
    public function deleteAuthor(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $authorID =  $request->attributes->get('id');
        $author = $entityManager->getRepository(Author::class)->findOneBy(['id'=>$authorID]);
        $entityManager->remove($author);
        $entityManager->flush();
        $data =[
            'MSG' => $authorID." Author has been successfully deleted !."
        ];
        return $this->json($data);
    }
}
