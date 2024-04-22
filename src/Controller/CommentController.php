<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    #[Route('/comment/create/{id}', name: 'app_comment_create')]
    public function create(Request $request, EntityManagerInterface $manager, Post $post):Response
    {
        if(!$this->getUser()){return $this->redirectToRoute("app_post");}


        $comment= new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setAuthor($this->getUser());

            $comment->setCreatedAt(new \DateTime());


            $comment->setPost($post);
            $manager->persist($comment);
            $manager->flush();

        }
        return $this->redirectToRoute("app_show",["id"=>$post->getId()]);
    }

    #[Route('/comment/delete/{id}', name: 'delete_comment')]
    public function delete(Comment $comment, EntityManagerInterface $manager): Response
    {
        if($this->getUser() === $comment->getAuthor()) {

            $post = $comment->getPost();

            $manager->remove($comment);
            $manager->flush();

            return $this->redirectToRoute('app_show', ["id" => $post->getId()]);
        }else{
            return $this->redirectToRoute('app_post');
        }


    }

    #[Route('/comment/edit/{id}', name: 'edit_comment')]
    public function edit(Request $request, EntityManagerInterface $manager, Comment $comment):Response
    {
        $post = $comment->getPost();
        $form = $this->createForm(CommentType::class, $comment);

        if($this->getUser() === $comment->getAuthor()) {


            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setCreatedAt(new \DateTime());

                $manager->persist($comment);
                $manager->flush();

                return $this->redirectToRoute("app_show", ["id" => $post->getId()]);
            }
        }else{
            return $this->redirectToRoute('app_post');
        }



        return $this->render('comment/edit.html.twig', [

            "form"=>$form->createView(),
        ]);

    }

    #[Route('/comment/image/{id}', name:"comment_image")]
    public function addImage(Comment $comment):Response
    {
        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);

        if($this->getUser() === $comment->getAuthor()) {

            return $this->render("comment/image.html.twig", [
                "comment" => $comment,
                'formImage' => $formImage->createView()

            ]);
        }else{
            return $this->redirectToRoute('app_article');
        }
    }
}