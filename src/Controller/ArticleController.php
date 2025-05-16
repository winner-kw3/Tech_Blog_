<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentTypeForm;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'article_show')]
    public function show(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        
        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTime());

        
        $form = $this->createForm(CommentTypeForm::class, $comment);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}