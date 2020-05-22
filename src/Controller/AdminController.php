<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\ImagePost;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    private function encodeUserPlainPassword($user)
    {
        $plainPassword = $user->getPlainPassword();

        if (!empty($plainPassword)) {
            $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->eraseCredentials();
        }
    }

    public function persistEntity($entity)
    {   
        if ($entity instanceof User) {
            $this->encodeUserPlainPassword($entity);
        }
        elseif($entity instanceof Post) {
            $user = $this->getUser();
            $entity->setUsuario($user);
            $entity->setPostDate(new \Datetime('now'));
            $entity->setPostModified(new \Datetime('now'));
        }
        
        parent::persistEntity($entity);
    }

    public function updateEntity($entity)
    {
        if ($entity instanceof User) {
            $this->encodeUserPlainPassword($entity);
        }
        elseif($entity instanceof Post) {
            $entity->setPostModified(new \Datetime('now'));
        }

        parent::updateEntity($entity);
    }

    protected function removeEntity($entity)
    {
        if ($entity instanceof Post) {
            $images = $this->em->getRepository(ImagePost::class)->findBy(['post' => $entity]);
            foreach ($images as $image) {
                $this->em->remove($image);
            }
        }
        parent::removeEntity($entity);
    }
}