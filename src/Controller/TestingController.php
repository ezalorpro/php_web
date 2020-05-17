<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TestingController extends AbstractController{

    /**
    * @Route("/testing", name="testing")
    */
    public function testing(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('testing.html.twig');
    }
}