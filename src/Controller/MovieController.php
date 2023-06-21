<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\ActorRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/movies', name: 'createMovie', methods: ['POST'])]
    public function createMovie(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        ActorRepository $actorRepository,
        WriterRepository $writerRepository,
        GenreRepository $genreRepository,
        ValidatorInterface $validator,
    ): JsonResponse
    {
        //? je deserialize la requete en Objet Movie::Class $movie
        $movie = $serializer->deserialize($request->getContent(), Movie::class, 'json');

        $errors = $validator->validate($movie);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        
        $content = $request->toArray();

        $idActor = $content['idActor'] ?? -1;
        $idWriter = $content['idWriter'] ?? -1;
        $idGenre = $content['idGenre'] ?? -1;

        $movie->addActor($actorRepository->find($idActor));
        $movie->addWriter($writerRepository->find($idWriter));
        $movie->addGenre($genreRepository->find($idGenre));        

        $em->persist($movie);
        $em->flush();


        $jsonMovie = $serializer->serialize($movie, 'json', ['groups' => 'getMovies']);

        $location = $urlGenerator->generate('detailMovie', ['id' => $movie->getId()], UrlGeneratorInterface::ABSOLUTE_URL);


        
        return new JsonResponse(
            $jsonMovie, 
            Response::HTTP_CREATED,
            ["Location" => $location],
            true
        );
    }

    #[Route('/movies/{id}', name: 'updateMovie', methods: ['PUT'])]
    public function updateMovie(
        Request $request,
        SerializerInterface $serializer,
        Movie $currentMovie,
        EntityManagerInterface $em,
        ActorRepository $actorRepository,
        WriterRepository $writerRepository,
        GenreRepository $genreRepository,
        $id,
    ): JsonResponse
    {

        $updatedMovie = $serializer->deserialize($request->getContent(), 
                Movie::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentMovie]);

        $content = $request->toArray();
        $idActor = $content['idActor'] ?? -1;
        $idWriter = $content['idWriter'] ?? -1;
        $idGenre = $content['idGenre'] ?? -1;

        $updatedMovie->addActor($actorRepository->find($idActor));
        $updatedMovie->addWriter($writerRepository->find($idWriter));
        $updatedMovie->addGenre($genreRepository->find($idGenre));  
        
        $em->persist($updatedMovie);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

