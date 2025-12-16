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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


final class StagesController extends AbstractController
{
    /**
     * Get tous les stages
     */
    #[Route('/stages', name: 'stages_get', methods: ['GET'])]
    public function index(StageRepository $unStageRepository,SerializerInterface $unSerialiseur): JsonResponse
    {
        $lesStages = $unStageRepository->findAll();
        $result = [
            'message' => 'OK',
            'data' => $lesStages
        ];
        $serializedResult = $unSerialiseur->serialize($result, 'json');
        return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * Get 1 stage par son id
     */
    #[Route('/stages/{id}', name: 'stages_get_id', methods: ['GET'])]
    public function getDetailStage(string $id, StageRepository $unStageRepository, SerializerInterface $unSerialiseur): JsonResponse
    {
        if (!is_numeric( $id)){
            return new JSONResponse(['message' => 'Id de ressource invalide'], JsonResponse::HTTP_BAD_REQUEST);

        }
        $unStage = $unStageRepository->find($id);
        if ($unStage === null) {
            return new JSONResponse(['message' => 'Ressource inexistante'], JsonResponse::HTTP_NOT_FOUND);
        }
        $result = [
            'message' => 'OK',
            'data' => $unStage
        ];
        $serializedResult = $unSerialiseur->serialize($result, 'json');

        return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * Post création d'un nouveau stage
     * VOIR LES VALIDATEUR !!!!!!!!!
     */
    #[Route('/stages', name: 'stages_post', methods: ['POST'])]
    public function createStage(Request $request, SerializerInterface $unSerialiseur,
    EntityManagerInterface $em, URLGeneratorInterface $unUrlGenerateur)
    {
        $contenu = $request->getContent();
        $unStage = $unSerialiseur->deserialize($contenu, Stage::class, 'json');
        $seeContent= [
            $unStage->getDescriptifMissions(),
            $unStage->getMoyens(),
            $unStage->getEtudiant(),
            $unStage->getNumeroOrganisation(),
            $unStage->getIdPeriodeStage()
        ];
        $count=0;
        for ($i = 0; $i < count($seeContent); $i++) {
            if (isset($seeContent[$i])) {
                $count += 1;
            }
        }
        if ($count == 5 &&
            is_numeric($seeContent[2]) && 
            is_numeric($seeContent[3]) && 
            is_numeric($seeContent[4])){
                $em->persist($unStage);
                $em->flush();
                $location = $unUrlGenerateur->generate('stages_post', 
                                        ['id' => $unStage->getId()],
                                        UrlGeneratorInterface::ABSOLUTE_URL);
                $id = $unStage->getId();
                $result = ["message" => "Stage d\'id {$id} créé",
                        "data" => [
                            "_selfLink" => $location
                            ]
                        ];
                return new JsonResponse($result, JSONResponse::HTTP_CREATED, [], false);
        }
        else {
            $result = [
                "message"=> "Les données fournies sont erronées",
                "erreurs"=>[]
            ];
            for ($i = 0; $i < count($seeContent); $i++) {
                if (!isset($seeContent[$i])) {
                    if ($i == 0){
                        $result["erreurs"] = "Descriptif mission non renseigner";
                    }
                    elseif ($i == 1) {
                        $result["erreurs"] = "Moyens non renseigner";
                    }
                    elseif ($i == 2) {
                        $result["erreurs"] = "Id étudiant non renseigner";
                    }
                    elseif ($i == 3) {
                        $result["erreurs"] = "Id Organisation non renseigner";
                    }
                    else{
                        $result["erreurs"] = "Id periode stage non renseigner";
                    }
                }
                elseif (is_numeric($seeContent[$i])) {
                    if ($i == 0 or $i == 1) {
                        continue;
                    }
                    else {
                        if ($i == 2) {
                            $result["erreurs"] = "Id étudiant invalide";
                        }
                        elseif ($i == 3) {
                            $result["erreurs"] = "Id Organisation invalide";
                        }
                        else{
                            $result["erreurs"] = "Id periode stage invalide";
                        }
                    }
                }
            }
            return new JsonResponse($result, JSONResponse::HTTP_BAD_REQUEST, [], false);
        }
    }
}
