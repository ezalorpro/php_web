<?php

namespace App\Controller;

use App\Form\LoginFormType;
use App\Form\ProfileFormType;
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
* @Route("/profile")
*/
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="user_profile")
     */
    public function profile(EntityManagerInterface $entityManager){   
        $user = $this->getUser();
        $Posts = $entityManager
                    ->getRepository(Post::class)
                    ->findBy(['usuario' => $user], ['post_date' => 'DESC']);

        return $this->render('profile/profile.html.twig', [
            'usuario' => $user, 'post_list' => $Posts
            ]
        );
    }

    /**
     * @Route("/edit", name="profile_edit")
     */
    public function edit_profile(EntityManagerInterface $entityManager, Request $request, FileUploader $fileUploader){
           
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $avatarFile = $form['avatar']->getData();

            if ($avatarFile) {
                if ($user->getAvatar()) {
                    $filesystem = new Filesystem();
                    try {
                        $filesystem->remove($user->getAvatarUrl());
                    } catch (FileException $e) {

                    }
                }
                $avatarFileName = $fileUploader->upload($avatarFile);
                $user->setAvatar($avatarFileName);
                $user->setAvatarUrl('/images');
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('profile/profile_edit.html.twig', [
            'perfilForm' => $form->createView(),
            'usuario' => $user
        ]);
    }
}