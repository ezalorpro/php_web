<?php
namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController{

    /**
    * @Route("/", name="home")
    */
    public function home(EntityManagerInterface $entityManager){
        $Posts = $entityManager->getRepository(Post::class)->findBy([], ['post_date' => 'DESC']);
        return $this->render('home.html.twig', ['posts' => $Posts]);
    }
}
