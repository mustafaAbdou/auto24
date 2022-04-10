<?php

namespace App\Repository;

use App\Entity\AuthorToBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuthorToBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthorToBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthorToBook[]    findAll()
 * @method AuthorToBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorToBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthorToBook::class);
    }


    public function getList()
    {
        $query = $this->_em->createQueryBuilder()
        ->select('a.id','a.name','b.id AS bookID',"b.title")
        ->from('App\Entity\Author','a')
        ->leftJoin('App\Entity\AuthorToBook', 'c', 'WITH', 'a.id = c.author_id')
        ->leftJoin('App\Entity\Book', 'b', 'WITH', 'b.id = c.book_id')
        ->where('1=1')
        ->getQuery()
        ->getResult();

        $result = [];
        for ($i=0; $i < count($query) ; $i++) { 
            $book[$query[$i]['id']][] = ["id"=> $query[$i]['bookID'], "title"=> $query[$i]['title']];
            $result['authors'][$query[$i]['name']] = ["id"=> $query[$i]['id'], "name"=> $query[$i]['name'],"books"=> $book[$query[$i]['id']] ];
    
        }
    
        return $result;
    }


    public function get_specific_Author($author_id)
    {
        $query = $this->_em->createQueryBuilder()
        ->select('a.id','a.name','b.id AS bookID',"b.title")
        ->from('App\Entity\Author','a')
        ->leftJoin('App\Entity\AuthorToBook', 'c', 'WITH', 'a.id = c.author_id')
        ->leftJoin('App\Entity\Book', 'b', 'WITH', 'b.id = c.book_id')
        ->where('1=1')
        ->andWhere('a.id = :author_id')
        ->setParameter('author_id', $author_id)
        ->getQuery()
        ->getResult();

        $result = [];
        $result['author']['id'] = $query[0]['id'];
        $result['author']['name'] = $query[0]['name'];
          
        for ($i=0; $i < count($query) ; $i++) { 
            
            $result['books'][] = array('book_id' => $query[$i]['bookID'], 'title' => $query[$i]['title'] );
            
        }
    
        return $result;
    
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AuthorToBook $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AuthorToBook $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return AuthorToBook[] Returns an array of AuthorToBook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AuthorToBook
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
