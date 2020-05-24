<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tags;
use App\Entity\User;
use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


/**
* @Route("/search")
*/
class SearchController extends AbstractController
{
    /**
    * @Route("/", name="app_search")
    */
    public function search(EntityManagerInterface $entityManager, Request $request){   
        
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $value = strtolower($form['search']->getData());
            $search_keys = array('Title' => false, 'Tags' => false, 'User' => false);
            
            foreach ($form['query_type']->getData() as $key) {
                $search_keys[$key] = true;
            }

            $results = $entityManager->getRepository(Post::class)
                ->findBySelection($value, $search_keys);
                
            return $this->render('search/search.html.twig', [
                'form' => $form->createView(),
                'results' => $results
            ]);
        }
        elseif($request->query->get('query')){
            $value = strtolower($request->query->get('query'));
            $form->get('search')->setData($value);
            
            if ($request->query->get('query_type') == 'all') {
                $search_keys = array('Title' => true, 'Tags' => true, 'User' => true);
            } elseif ($request->query->get('query_type') == 'Tags') {
                $search_keys = array('Title' => false, 'Tags' => true, 'User' => false);
            }
            
            $results = $entityManager->getRepository(Post::class)
                ->findBySelection($value, $search_keys);
                
            return $this->render('search/search.html.twig', [
                'form' => $form->createView(),
                'results' => $results
            ]);
        }

        return $this->render('search/search.html.twig', [
                'form' => $form->createView(),
                'results' => false
            ]); 
    }
}
