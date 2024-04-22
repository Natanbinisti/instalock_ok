<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Post;
use App\Entity\ReplyComment;
use App\Entity\User;
use App\Form\ImageType;
use App\Form\ReplyCommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\ReplyCommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReplyCommentController extends AbstractController
{
    #[Route('/reply/comment/{id}', name: 'app_reply_comment')]
    public function index($id, Request $request, EntityManagerInterface $manager, Comment $comment): Response
    {
        if (!$this->getUser()) {

            return $this->redirectToRoute("app_post");
        }

        $post = $comment->getPost();

        $replyComment = new ReplyComment();
        $form = $this->createForm(ReplyCommentType::class, $replyComment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $replyComment->setPost($post);
            $replyComment->setComment($comment);
            $manager->persist($replyComment);
            $manager->flush();

        }
        return $this->redirectToRoute("app_show", ["id" => $post->getId()]);
    }








}