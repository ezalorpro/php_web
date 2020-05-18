<?php

namespace App\Controller;

use App\Form\LoginFormType;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
* @Route("/profile")
*/
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="user_profile")
     */
    public function profile(EntityManagerInterface $entityManager)
    {   
        $user = $this->getUser();
        $Post = $entityManager->getRepository(Post::class)->findAll();

        return $this->render('profile/profile.html.twig', [
            'usuario' => $user, 'post_list' => $Post
            ]
        );
    }

    // /**
    //  * @Route("/logout", name="app_logout")
    //  */
    // public function logout()
    // {
    //     throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    // }
}