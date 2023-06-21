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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator,
    ): JsonResponse
    {
        //? je deserialize la requete en Objet Movie::Class $movie
        $actor = $serializer->deserialize($request->getContent(), Actor::class, 'json');

        $errors = $validator->validate($actor);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($actor);
        $em->flush();

        //? je renvoie un json car un film vient d'être créé donc y'a  un id
        $jsonActor = $serializer->serialize($actor, 'json', ['groups' => 'getActors']);

        // creer URL
        $location = $urlGenerator->generate('detailActor', ['id' => $actor->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse(
            $jsonActor, 
            Response::HTTP_CREATED,
            ["Location" => $location],
            true
        );
    }

    #[Route('/actors/{id}', name: 'updateActors', methods: ['PUT'])]
    public function updateActor(
        Request $request,
        SerializerInterface $serializer,
        Actor $currentActor,
        EntityManagerInterface $em,
        $id,
    ): JsonResponse
    {

        $updatedActor= $serializer->deserialize($request->getContent(), 
                Actor::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentActor]);

        $em->persist($updatedActor);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}
