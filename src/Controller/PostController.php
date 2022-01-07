<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
// use App\Services\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', name: 'post.')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts            
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, FileUploader $fileUploader) {
        // create a new post with title
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        

        if ($form->isSubmitted()) {
            // entity manager
            $em = $this->container->get('doctrine')->getManager();
            
            /** @var UploadedFile $file */
            $file = $request->files->get('post')['attachment'];
            if ($file) {
                
                $fileName = $fileUploader->uploadFile($file);

                $post->setImage($fileName);
                $em->persist($post);
                $em->flush();        
            }
            
            return $this->redirect($this->generateUrl('post.index'));
        }

        
        
        // return a response
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    

    #[Route('/show/{id}', name: 'show')]
    public function show(Post $post) {

        // create the show view
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function remove(Post $post) {
        $em = $this->container->get('doctrine')->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post was removed');

        return $this->redirect($this->generateUrl('post.index'));
    }
}
