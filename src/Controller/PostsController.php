<?php

namespace App\Controller;

use App\Form\PostFormType;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Tags;
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
class PostsController extends AbstractController{   
    
    /**
     * @Route("/", name="posts_list")
     */
    public function posts_list(EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $Posts = $entityManager->getRepository(Post::class)->findBy(['usuario' => $user]);
        return $this->render('posts/posts_list.html.twig', ['post_list' => $Posts,]);
    }

    /**
    * @Route("/create/", name="post_create")
     */
    public function post_create(Request $request, EntityManagerInterface $entityManager): Response 
    {
        $form = $this->createForm(PostFormType::class);
        $form->handleRequest($request);
        $post = null;

        // request handling

        $tags_obj = $entityManager->getRepository(Tags::class)->findAll();
        $tags = [];

        foreach ($tags_obj as $key => $tag) {
            $tags[$tag] = null;
        }

        $tags = $tags;
        
        $current_tags = [];
        return $this->render('posts/post_create.html.twig', [
            'post_form' => $form->createView(),
            'post' => $post,
            'tags' => $tags,
            'current_tags' => $current_tags
            ] 
        );
    }

    /**
     * @Route("/{post_id}/", name="post_view")
     */
    public function post_view(string $post_id, EntityManagerInterface $entityManager){
        $Post = $entityManager->getRepository(Post::class)->findOneBy(['id' => intval($post_id)]);
        return $this->render('posts/post_view.html.twig', ['post' => $Post,] );
    }

}