<?php

// $my_string = ['hola', 'pelispedia', 'www'];
// foreach ($my_string as $tag) {
//     $current_tag[] = ['tag' => $tag];
// }
// print_r(json_encode($current_tag));

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