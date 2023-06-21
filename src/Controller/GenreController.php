<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class GenreController extends AbstractController
{
    #[Route('/genres', name: 'genres', methods:['GET'])]
    public function getAllGenres(
        GenreRepository $genreRepository,
        SerializerInterface $serialize,
    ): JsonResponse
    {

        $listGenre =  $genreRepository->findAll();

        $jsonListGenre = $serialize->serialize($listGenre, 'json', ['groups' => 'getGenres']);

        return new JsonResponse(
            $jsonListGenre,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/genres/{id}', name: 'detailGenre', methods: ['GET'])]
    public function getDetailGenre(
        GenreRepository $genreRepository,
        SerializerInterface $serializer,
        $id,
    ): JsonResponse
    {
        $genre = $genreRepository->findBy(['id' => $id]);

        if($genre){
            $jsonGenre = $serializer->serialize($genre, 'json', ['groups' => 'getGenres']);

        return new JsonResponse(
            $jsonGenre,
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

    #[Route('/genres/{id}', name: 'deleteGenre', methods: ['DELETE'])]
    public function deleteGenres(
        Genre $genre,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        $em->remove($genre);
        $em->flush();
        
        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT,
        );
    }

    #[Route('/genres', name: 'createGenre', methods: ['POST'])]
    public function createActor(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        //? je deserialize la requete en Objet Movie::Class $movie
        $genre = $serializer->deserialize($request->getContent(), Genre::class, 'json');

        $em->persist($genre);
        $em->flush();

        //? je renvoie un json car un film vient d'être créé donc y'a  un id
        $jsonGenre = $serializer->serialize($genre, 'json', ['groups' => 'getGenres']);

        return new JsonResponse(
            $jsonGenre, 
            Response::HTTP_CREATED,
            [],
            true
        );
    }
}
