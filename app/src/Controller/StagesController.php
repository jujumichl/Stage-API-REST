<?php

namespace App\Controller;

use App\Entity\Stage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\StageRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\FraisForfait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


final class StagesController extends AbstractController
{
    #[Route('/stages', name: 'stages_get', methods: ['GET'])]
    public function index(StageRepository $unStage,SerializerInterface $unSerialiseur): JsonResponse
    {
        $lesStages = $unStage->findAll();
        $result = [
            'message' => 'OK',
            'data' => $lesStages
        ];
        $serializedResult = $unSerialiseur->serialize($result, 'json');
        return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/stages/{id}', name: 'stages_get_id', methods: ['GET'])]
    public function getDetailStage(string $id, StageRepository $unStageRepository, SerializerInterface $unSerialiseur): JsonResponse
    {
        $unStage = $unStageRepository->find($id);
        if ($unStage === null) {
            return new JSONResponse(['message' => 'Id frais forfait inexistant'], JsonResponse::HTTP_NOT_FOUND);
        }
        $result = [
            'message' => 'OK',
            'data' => $unStage
        ];
        $serializedResult = $unSerialiseur->serialize($result, 'json');

        return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/stages', name: 'stages_post', methods: ['POST'])]
    public function createStage(Request $request, StageRepository $unstageRepository, SerializerInterface $unSerialiseur,
    EntityManagerInterface $em, URLGeneratorInterface $unUrlGenerateur)
    {
        $contenu = $request->getContent();
        $unStage = $unSerialiseur->deserialize($contenu, Stage::class, 'json');
        $id = $unStage->getId();
        $dejaPresent = $unstageRepository->find($id);
        //if ($this->dejaPresent($contenu, $unFraisForfaitRepository, $unSerialiseur)){
        if ($dejaPresent === null) {
            $em->persist($unStage);
            $em->flush();
                $location = $unUrlGenerateur->generate('fraisforfaits_post', 
                                        ['id' => $unStage->getId()],
                                        UrlGeneratorInterface::ABSOLUTE_URL);

                $result = ["message" => "Nouveau frais forfait créé",
                        "data" => [
                            "_selfLink" => $location
                            ]
                        ];
                return new JsonResponse($result, JSONResponse::HTTP_CREATED, [], false);
        }
        else {
            $result = ["message" => "Id frais forfait déjà existant"];
            return new JsonResponse($result, JSONResponse::HTTP_CONFLICT, [], false);
        }
    }



}
