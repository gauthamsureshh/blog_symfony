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
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BlogController extends AbstractController
{
    //function display all the blogs in db
    #[Route('/blog', name: 'app_blog')]
    public function index(BlogPostRepository $blogs): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs->findAll()
        ]);
    }

    //function create new blog and save them in db
    #[Route('/createblog', name: 'app_blog_create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function store(Request $request, EntityManagerInterface $manager): Response 
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'); // unless the there is a authenticated user, the page will be redirected to login page
        $form = $this->createForm(BlogType::class, new BlogPost );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $posts = $form->getData();
            $posts->setAuthor($this->getUser());

            $manager->persist($posts, true);
            $manager->flush();

            flash()->success('New Blog Created Successfully');

            return $this->redirectToRoute('app_blog');
        }

        return $this->render(
            'blog/createBlog.html.twig',
            [
                'form' => $form
            ]
        );
    }

    //function to edit exsiting blog details
    #[Route('/editblog/{id}', name: 'app_blog_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, BlogPost $post): Response
    {
        $form = $this->createForm(BlogType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $posts = $form->getData();

            $manager->persist($posts, true);
            $manager->flush();
    
            flash()->success('Blog Edited Successfully');

            return $this->redirectToRoute('app_blog');
        }

        return $this->render(
            'blog/editBlog.html.twig',
            [
                'form' => $form
            ]
        );
        
    }

    //function to delete exisiting blog
    #[Route('/deleteblog/{id}', name: 'app_blog_delete')]
    public function delete(EntityManagerInterface $manager, int $id): Response
    {
        
        $post = $manager->getRepository(BlogPost::class)->find($id);
        if(!$post){
            throw $this->createNotFoundException('Post Not Found');
        }

        $manager->remove($post);
        $manager->flush();

        flash()->error('Blog Deleted Successfully');

        return $this->redirectToRoute('app_blog');
    }
}
