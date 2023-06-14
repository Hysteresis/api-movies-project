<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class MovieController extends AbstractController
{
    #[Route('/movies', name: 'movies', methods:['GET'])]
    public function getAllMovies(
        MovieRepository $movieRepository,
        SerializerInterface $serializer,
        ): JsonResponse
    {

        $movieList = $movieRepository->findAll();

        $jsonMovieList = $serializer->serialize(
            $movieList, 
            'json',
            ['groups' => 'getMovies']
            
        );

        return new JsonResponse(
            $jsonMovieList,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/movies/{id}', name:'detailMovie', methods: ['GET'])]
    public function getDetailMovie(
        MovieRepository $movieRepository,
        SerializerInterface $serializer,
        $id,
        ): JsonResponse
    {

        $movie = $movieRepository->findBy(['id' => $id]);

        if($movie){
            $jsonMovie = $serializer->serialize($movie, 'json', ['groups' => 'getMovies']);
            
            return new JsonResponse(
                $jsonMovie,
                Response::HTTP_OK,
                [],
                true
            );
        } 

        return new JsonResponse(
            null,
            Response::HTTP_NOT_FOUND,
        );
    }

    #[Route('/movies/{id}', name: 'deleteMovie', methods: ['DELETE'])]
    public function deleteMovie(
        Movie $movie,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        $em->remove($movie);
        $em->flush();
        
        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT,
        );
    }
}

