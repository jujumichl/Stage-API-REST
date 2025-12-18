<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Entity\Organisation;
use App\Entity\Etudiant;
use App\Entity\Periode;
use App\Repository\StageRepository;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StagesController extends AbstractController
{
    /**
     * Get tous les stages
     */
    #[Route('/stages', name: 'stages_get', methods: ['GET'])]
    public function index(StageRepository $unStageRepository, SerializerInterface $unSerialiseur): JsonResponse
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
        if (!is_numeric($id)) {
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
    public function createStage(
        Request $request,
        SerializerInterface $unSerialiseur,
        EntityManagerInterface $em,
        URLGeneratorInterface $unUrlGenerateur,
        ValidatorInterface $unValidator
    ) {
        $contenu = $request->getContent();
        try{
            $data = $request->toArray();
            $organisationId = $data['idOrganisation'];
            $etudiantId = $data['idEtudiant'];
            $periodeId = $data['idPeriodeStage'];
            $Repo = [
                "Organisation" => $em->getRepository(Organisation::class)->find($organisationId),
                "Etudiant" => $em->getRepository(Etudiant::class)->find($etudiantId),
                "Periode" => $em->getRepository(Periode::class)->find($periodeId)
            ];
            if ($Repo["Organisation"] !== null && $Repo["Etudiant"] !== null && $Repo["Periode"] !== null ){
                    $unStage = $unSerialiseur->deserialize($contenu, Stage::class, 'json');
                    $errorsStage = $unValidator->validate($unStage);

                $messages = test($errorsStage);
                if (!empty($messages)) {

                    $result = ["message" => "Données erronées", "errors" => $messages];

                    return new JsonResponse($result, JsonResponse::HTTP_BAD_REQUEST, [], false);
                } else {
                    $unStage->setEtudiant($Repo["Etudiant"]);
                    $unStage->setOrganisation($Repo["Organisation"]);
                    $unStage->setPeriodeStage($Repo["Periode"]);
                    $em->persist($unStage);

                    $em->flush();
                    $location = $unUrlGenerateur->generate(
                        'stages_post',
                        ['id' => $unStage->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );
                    $id = $unStage->getId();
                    $result = [
                        "message" => "Stage d'id {$id} créé",
                        "data" => [
                            "_selfLink" => $location
                        ]
                    ];
                    return new JsonResponse($result, JSONResponse::HTTP_CREATED, [], false);
                }
            }
            else {
                if ($Repo["Organisation"] == null){
                    $messages[] = "L'identifiant de Organisation est invalide";
                }
                else if ($Repo["Etudiant"] == null){
                    $messages[] = "L'identifiant de Etudiant est invalide";
                } 
                else {
                    $messages[] = "L'identifiant de Periode Stage est invalide";
                }

                $result = ["message" => "Données erronées", "errors" => $messages];

                return new JsonResponse($result, JsonResponse::HTTP_BAD_REQUEST, [], false);
            }
        }
        catch (Exception $e){
            $result = ["message" => "Données erronées", "errors" => $e->getMessage()];
            return new JsonResponse($result, JsonResponse::HTTP_BAD_REQUEST, [], false);
        }
    }
}
function test($errors)
{
    $messages = [];
    foreach ($errors as $error) {
        $messages[] = [$error->getPropertyPath() => $error->getMessage()];
    }
    return $messages;
}
