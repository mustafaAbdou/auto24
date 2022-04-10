<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\AuthorToBook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function getAll(): object
    {
        $entityManager = $this->getDoctrine()->getManager();

        $result = $entityManager->getRepository(AuthorToBook::class)->getList();

 
        return $this->json($result);

    }

    public function getspecificAuthor(Request $request): object
    {
        $entityManager = $this->getDoctrine()->getManager();
        $author_id =  $request->attributes->get('id');
        if(!isset($author_id)){
            return $this->json('Error, should not be empty');
        }
        $result = $entityManager->getRepository(AuthorToBook::class)->get_specific_Author($author_id);

        return $this->json($result);

    }

    
    public function assignBookToAuthor(Request $request): object
    {
        $entityManager = $this->getDoctrine()->getManager();

        $bodyParam = json_decode($request->getContent(), false);

        $assign = new AuthorToBook();

        $assign->setBookId($bodyParam->book_id);
        $assign->setAuthorId($bodyParam->author_id);
        $entityManager->persist($assign);
        $entityManager->flush();

        $data =[
               'Author_id' => $bodyParam->author_id,
               'Book_id' => $bodyParam->book_id
           ];
        return $this->json($data);
    }
}
