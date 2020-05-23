<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findBySelection($value, $search_keys)
    {   
        $qb = $this->createQueryBuilder('p');

        if ($search_keys['Title']) {
            $qb
                ->orWhere('LOWER(p.title) LIKE :title')
                ->setParameter('title', '%'.$value.'%');
        }

        if ($search_keys['Tags']) {
            $qb
                ->innerJoin('p.tags', 'tags')
                ->orWhere('LOWER(tags.name) LIKE :name')
                ->setParameter('name', '%'.$value.'%');
        }

        if ($search_keys['User']) {
            $qb
                ->innerJoin('p.usuario', 'usuario')
                ->orWhere('LOWER(usuario.username) LIKE :username')
                ->setParameter('username', '%'.$value.'%');
        }

        return $qb
            ->orderBy('p.post_date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByAll($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.tags', 'tags')
            ->innerJoin('p.usuario', 'usuario')
            ->Where('LOWER(p.title) LIKE :title')
            ->orWhere('LOWER(tags.name) LIKE :name')
            ->orWhere('LOWER(usuario.username) LIKE :username')
            ->setParameter('title', '%'.$value.'%')
            ->setParameter('name', '%'.$value.'%')
            ->setParameter('username', '%'.$value.'%')
            ->orderBy('p.post_date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTitle($value)
    {
        return $this->createQueryBuilder('p')
            ->Where('LOWER(p.title) LIKE :title')
            ->setParameter('title', '%'.$value.'%')
            ->orderBy('p.post_date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTags($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.tags', 'tags')
            ->Where('LOWER(tags.name) LIKE :name')
            ->setParameter('name', '%'.$value.'%')
            ->orderBy('p.post_date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUser($value)
    {   
        return $this->createQueryBuilder('p')
            ->innerJoin('p.usuario', 'usuario')
            ->Where('LOWER(usuario.username) LIKE :username')
            ->setParameter('username', '%'.$value.'%')
            ->orderBy('p.post_date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
