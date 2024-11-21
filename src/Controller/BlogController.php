<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogType;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(BlogPostRepository $blogs): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs->findAll()
        ]);
    }

    #[Route('/createblog', name: 'app_blog_create')]
    public function store(Request $request, EntityManagerInterface $manager): Response 
    {
        $form = $this->createForm(BlogType::class, new BlogPost );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $posts = $form->getData();

            $manager->persist($posts, true);
            $manager->flush();

            $this->addFlash('success', 'New Blog Created Successfully');

            return $this->redirectToRoute('app_blog');
        }

        return $this->render(
            'blog/createBlog.html.twig',
            [
                'form' => $form
            ]
        );
    }
}
