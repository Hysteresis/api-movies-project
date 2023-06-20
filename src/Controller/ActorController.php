<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ActorController extends AbstractController
{
    #[Route('/actors', name: 'actors', methods: ['GET'])]
    public function getAllActors(
        ActorRepository $actorRepository,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        //recupere objet de tous les actors
        $listActor = $actorRepository->findAll();

        // Transoform objet listACtors en JSON
        $jsonListActor = $serializer->serialize($listActor , 'json', ['groups' => 'getActors']);


        return new JsonResponse(
            $jsonListActor,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/actors/{id}', name: 'detailActor', methods: ['GET'])]
    public function getDetailActor(
        ActorRepository $actorRepository,
        SerializerInterface $serializer,
        $id,
    ): JsonResponse
    {
        $actor = $actorRepository->findBy(['id' => $id]);

        if($actor){
            $jsonActor = $serializer->serialize($actor, 'json', ['groups' => 'getActors']);

        return new JsonResponse(
            $jsonActor,
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

    #[Route('/actors/{id}', name: 'deleteActor', methods: ['DELETE'])]
    public function deleteActor(
        Actor $actor,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        $em->remove($actor);
        $em->flush();
        
        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT,
        );
    }

    #[Route('/actors', name: 'createActor', methods: ['POST'])]
    public function createActor(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        //? je deserialize la requete en Objet Movie::Class $movie
        $actor = $serializer->deserialize($request->getContent(), Actor::class, 'json');

        $em->persist($actor);
        $em->flush();

        //? je renvoie un json car un film vient d'être créé donc y'a  un id
        $jsonActor = $serializer->serialize($actor, 'json', ['groups' => 'getActors']);

        return new JsonResponse(
            $jsonActor, 
            Response::HTTP_CREATED,
            [],
            true
        );
    }


}
