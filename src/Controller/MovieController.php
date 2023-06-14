<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MovieController extends AbstractController
{
    #[Route('/api/movies', name: 'app_movies', methods:['GET'])]
    public function getMoviesList(
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

    #[Route('/api/movies/{slug}', name:'app_detail_movie', methods: ['GET'])]
    public function getDetailMovie(
        MovieRepository $movieRepository,
        SerializerInterface $serializer,
        $slug,
        ): JsonResponse
    {

        $movie = $movieRepository->findBy(['slug' => $slug]);

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
}

