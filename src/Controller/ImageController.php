<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Post;
use App\Entity\User;
use App\Form\ImageType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route('/image/add/post/{id}', name: 'add_post_image')]
    #[Route('/image/add/comment/{id}', name: 'add_comment_image')]
    #[Route('/avatar/add/user/{id}', name: 'add_user_avatar')]
    public function index($id, Request $request, EntityManagerInterface $manager, PostRepository $postRepository, CommentRepository $commentRepository, UserRepository $userRepository): Response
    {

        //determiner la route utilisée
        $route = $request->attributes->get("_route");

        $toBeAddedAnImage= null;
        $toBeAddedAPost = null;

        //en fonction de la route, récuperer la bonne entité

        switch ($route){

            case 'add_post_image':
                $entity = Post::class;
                $toBeAddedAPost = $manager->getRepository($entity)->find($id);
                $setter = "setPost";
                $redirectRoute = "post_image";
                $routeParam= ["id"=>$id];
                break;
            case 'add_user_avatar':
                $entity = User::class;
                $toBeAddedAnImage = $userRepository->find($id);
                $existingImage = $toBeAddedAnImage->getImage();
                $setter = "setAvatar";
                $redirectRoute = "user_avatar";
                $routeParam= ["id"=>$id];
                break;
            case 'add_comment_image':
                $entity = Comment::class;
                $toBeAddedAnImage = $commentRepository->find($id);
                $existingImage = $toBeAddedAnImage->getImage();
                $setter = "setComment";
                $redirectRoute = "comment_image";
                $routeParam= ["id"=>$id];
                break;



        }


        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);
        if($formImage->isSubmitted() && $formImage->isValid())
        {
            if($toBeAddedAnImage !==null) {
                if ($existingImage->count() > 0) {
                    $manager->remove($existingImage->first());
                    $manager->flush();
                }


                $image->$setter($toBeAddedAnImage);
                $manager->persist($image);
                $manager->flush();
            }
            elseif ($toBeAddedAPost !==null){
                $image->$setter($toBeAddedAPost);
                $manager->persist($image);
                $manager->flush();

            }

        }



        return $this->redirectToRoute($redirectRoute, $routeParam);
    }

    #[Route('/image/delete/post/{id}', name: 'delete_post_image')]
    #[Route('/image/delete/comment/{id}', name: 'delete_comment_image')]
    #[Route('/image/delete/avatar/{id}', name: 'delete_user_avatar')]
    public function delete(EntityManagerInterface $manager, Image $image,Request $request){

        $route = $request->attributes->get("_route");

        switch ($route){

            case 'delete_post_image':
                $post = $image->getPost();
                $redirectRoute = "post_image";
                $routeParam= ["id"=>$post->getId()];
                break;
            case 'delete_user_avatar':
                $avatar = $image->getAvatar();
                $redirectRoute = "user_avatar";
                $routeParam= ["id"=>$avatar->getId()];
                break;
            case 'delete_comment_image':
                $comment = $image->getComment();
                $redirectRoute = "comment_image";
                $routeParam= ["id"=>$comment->getId()];
                break;



        }
        $manager->remove($image);
        $manager->flush();


        return $this->redirectToRoute($redirectRoute, $routeParam);

    }
}