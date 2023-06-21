<?php

namespace App\Controller;

use App\Entity\Writer;
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

#[Route('/api')]
class WriterController extends AbstractController
{
    #[Route('/writers', name: 'writers', methods: ['GET'])]
    public function getAllWriters(
        WriterRepository $writerRepository,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        //recupere objet de tous les actors
        $listWriter = $writerRepository->findAll();

        // Transoform objet listACtors en JSON
        $jsonListWriter = $serializer->serialize($listWriter , 'json', ['groups' => 'getWriters']);


        return new JsonResponse(
            $jsonListWriter,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/writers/{id}', name: 'detailWriter', methods: ['GET'])]
    public function getDetailWriter(
        WriterRepository $writerRepository,
        SerializerInterface $serializer,
        $id,
    ): JsonResponse
    {
        $writer = $writerRepository->findBy(['id' => $id]);

        if($writer){
            $jsonWriter = $serializer->serialize($writer, 'json', ['groups' => 'getWriters']);

        return new JsonResponse(
            $jsonWriter,
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

    #[Route('/writers/{id}', name: 'deleteWriter', methods: ['DELETE'])]
    public function deleteActor(
        Writer $writer,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        $em->remove($writer);
        $em->flush();
        
        return new JsonResponse(
            null, 
            Response::HTTP_NO_CONTENT,
        );
    }

    #[Route('/writers', name: 'createWriter', methods: ['POST'])]
    public function createActor(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
    ): JsonResponse
    {
        //? je deserialize la requete en Objet Movie::Class $movie
        $writer = $serializer->deserialize($request->getContent(), Writer::class, 'json');

        $em->persist($writer);
        $em->flush();

        //? je renvoie un json car un film vient d'être créé donc y'a  un id
        $jsonActor = $serializer->serialize($writer, 'json', ['groups' => 'getActors']);

        $location = $urlGenerator->generate('detailWriter', ['id' => $writer->getId()], UrlGeneratorInterface::ABSOLUTE_URL);


        return new JsonResponse(
            $jsonActor, 
            Response::HTTP_CREATED,
            ["Location" => $location],
            true
        );
    }

    #[Route('/writers/{id}', name: 'updateWriters', methods: ['PUT'])]
    public function updateWriter(
        Request $request,
        SerializerInterface $serializer,
        Writer $currentWriter,
        EntityManagerInterface $em,
        $id,
    ): JsonResponse
    {

        $updatedWriter= $serializer->deserialize($request->getContent(), 
                Writer::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentWriter]);

        $em->persist($updatedWriter);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}


