<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Post;
use App\Entity\ReplyComment;
use App\Form\CommentType;
use App\Form\ImageType;
use App\Form\PostType;
use App\Form\ReplyCommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(PostRepository $postRepository): Response
    {



        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            "posts"=>$postRepository->findAll(),
        ]);
    }

    #[Route('/create/post', name: 'app_create_post')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        /*if(!$this->getUser()){return $this->redirectToRoute("app_post");}*/


        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $post->setAuthor($this->getUser());
            $post->setCreatedAt(new \DateTime());




            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute("app_post");
        }




        return $this->render('post/create.html.twig', [
            "post"=>$post,
            "form"=>$form->createView(),
            "btnValue"=>"Poster",

        ]);
    }

    #[Route('/post/show/{id}', name: 'app_show')]
    public function show(Post $post): Response
    {

        $replyForms = [];

        foreach ($post->getComments() as $commentItem) {
            $replyForm = $this->createForm(ReplyCommentType::class, new ReplyComment());
            $replyForms['replyForm_' . $commentItem->getId()] = $replyForm->createView();
        }

        $replyCommentForm = $this->createForm(ReplyCommentType::class, new ReplyComment());


        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);



        return $this->render('post/show.html.twig', [
            "post"=>$post,
            "form"=>$form->createView(),
            'replyForms' => $replyForms,
            'replyCommentForm' => $replyCommentForm->createView(),

        ]);
    }

    #[Route('/post/delete/{id}', name: 'app_delete_post')]
    public function delete(EntityManagerInterface $manager, Post $post):Response
    {
        if($this->getUser() === $post->getAuthor()) {


            $manager->remove($post);
            $manager->flush();

            return $this->redirectToRoute("app_post");
        }else{
            return $this->redirectToRoute("app_post");

        }







    }

    #[Route('/post/edit/{id}', name: 'app_edit_post')]
    public function edit(Request $request, EntityManagerInterface $manager, Post $post):Response
    {
        $form = $this->createForm(PostType::class, $post);

        if($this->getUser() === $post->getAuthor()) {


            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $post->setCreatedAt(new \DateTime());

                $manager->persist($post);
                $manager->flush();

                return $this->redirectToRoute("app_show", ["id" => $post->getId()]);
            }
        }else{
            return $this->redirectToRoute('app_post');
        }





        return $this->render('post/create.html.twig', [
            "form"=>$form->createView(),
            "btnValue"=>"Editer"
        ]);

    }

    #[Route('/post/images/{id}', name:"post_image")]
    public function addImage(Post $post):Response
    {
        if($this->getUser() === $post->getAuthor()) {

            $image = new Image();
            $formImage = $this->createForm(ImageType::class, $image);

            return $this->render("post/image.html.twig", [
                "post" => $post,
                'formImage' => $formImage->createView()

            ]);
        }else{
            return $this->redirectToRoute('app_post');
        }
    }

    public function getRetwoot(): ?self
    {
        return $this->retwoot;
    }

    public function setRetwoot(?self $retwoot): static
    {
        $this->retwoot = $retwoot;

        return $this;
    }
    #[Route('/retweet/{id}', name: 'app_retweet')]
    public function retweet(Request $request, EntityManagerInterface $manager, Post $post): Response
    {
        $retweet = new Post();

        $form = $this->createForm(PostType::class, $retweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $retweet->setRetweet($post);
            $retweet->setCreatedAt(new \DateTime());
            $retweet->setAuthor($this->getUser());
            $manager->persist($retweet);
            $manager->flush();

            return $this->redirectToRoute('app_post');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
            'btnValue' => "Retweet"
        ]);
    }
}