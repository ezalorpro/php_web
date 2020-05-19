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
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
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
        $Posts = $entityManager
                    ->getRepository(Post::class)
                    ->findBy(['usuario' => $user], ['post_date' => 'DESC']);
        
        return $this->render('posts/posts_list.html.twig', ['post_list' => $Posts,]);
    }

    /**
    * @Route("/create/", name="post_create")
     */
    public function post_create(Request $request, EntityManagerInterface $entityManager): Response 
    {   
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        // $post = null;

        if ($form->isSubmitted() && $form->isValid()) {

            $tag_list = array_map(
                'strtolower',
                array_map(
                    'trim',
                    explode(
                        ",",
                        $form->get('tags')->getData()
                        )
                    )
                );
            
            foreach ($tag_list as $tag) {
                $tag_obj = $entityManager
                                ->getRepository(Tags::class)
                                ->findOneBy(['name' => $tag]);
                if ($tag_obj) {
                    $post->addTag($tag_obj);
                } else {
                    $tag_obj = new Tags();
                    $tag_obj->setName($tag);
                    $entityManager->persist($tag_obj);
                    $post->addTag($tag_obj);
                }
            }
            
            $post->setUsuario($this->getUser());
            $post->setPostDate(new \DateTime());
            $post->setPostModified(new \DateTime());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_view', ['post_id' => $post->getId()]);
        }

        $tags_obj = $entityManager->getRepository(Tags::class)->findAll();
        $tags = [];
        $current_tags = [];

        foreach ($tags_obj as $tag_obj) {
            $tags[$tag_obj->getName()] = null;
        }
        
        return $this->render('posts/post_create.html.twig', [
            'post_form' => $form->createView(),
            'post' => $post,
            'tags' => $tags,
            'current_tags' => $current_tags
            ] 
        );
    }

    /**
     * @Route("/delete/{post_id}/", name="post_delete")
     */
    public function post_delete(string $post_id, EntityManagerInterface $entityManager, Request $request)
    {   
        $token = $request->request->get('_csrf_token');

        if ($this->isCsrfTokenValid('delete-post', $token)) {        
            $post = $entityManager->getRepository(Post::class)->find($post_id);
            $entityManager->remove($post);
            $entityManager->flush();
            $referer = $request->headers->get('referer');
            if ($referer !== null) {
                return $this->redirect($referer);
            } else {
                return $this->redirectToRoute('home');
            }
        } else {
            throw new InvalidCsrfTokenException();
        }
    }

    /**
    * @Route("/edit/{post_id}/", name="post_edit")
    */
    public function post_edit(string $post_id, Request $request, EntityManagerInterface $entityManager): Response 
    {   
        $post = $entityManager->getRepository(Post::class)->find($post_id);
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tag_list = array_map(
                'strtolower',
                array_map(
                    'trim',
                    explode(",", $form->get('tags')->getData())
                    )
                );
            
            foreach ($post->getTags() as $tag_c) {
                $post->removeTag($tag_c);
            }

            foreach ($tag_list as $tag) {
                $tag_obj = $entityManager
                                ->getRepository(Tags::class)
                                ->findOneBy(['name' => $tag]);
                if ($tag_obj) {
                    $post->addTag($tag_obj);
                } else {
                    $tag_obj = new Tags();
                    $tag_obj->setName($tag);
                    $entityManager->persist($tag_obj);
                    $post->addTag($tag_obj);
                }
            }
            
            $post->setPostModified(new \DateTime());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_view', ['post_id' => $post->getId()]);
        }

        $tags_obj = $entityManager->getRepository(Tags::class)->findAll();
        $post_tags = $post->getTags();
        $tags = [];

        foreach ($tags_obj as $tag_obj) {
            $tags[$tag_obj->getName()] = null;
        }

        foreach ($post_tags as $tags_c) {
            $current_tags[] = ['tag' => $tags_c->getName()];
        }
        
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
        $Post = $entityManager->getRepository(Post::class)->find($post_id);
        return $this->render('posts/post_view.html.twig', ['post' => $Post,] );
    }
}