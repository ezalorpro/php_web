<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
* @Route("/posts")
*/
class PostsController extends AbstractController
{
    /**
     * @Route("/{post_id}", name="view_post")
     */
    public function profile(string $post_id, EntityManagerInterface $entityManager){
        $Post = $entityManager->getRepository(Post::class)->findOneBy(['id' => $post_id]);

        return $this->render('posts/post_view.html.twig', [
            'post' => $Post,
            ]
        );
    }

    // /**
    //  * @Route("/edit", name="profile_edit")
    //  */
    // public function edit_profile(EntityManagerInterface $entityManager)
    // {   
    // }
}