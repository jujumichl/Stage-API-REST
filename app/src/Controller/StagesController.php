<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Entity\Organisation;
use App\Entity\Etudiant;
use App\Entity\Periode;
use App\Repository\SpecialiteRepository;
use App\Repository\StageRepository;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class StagesController extends AbstractController
{
    /**
     * Get tous les stages
     * 
     * Ajout de la recherche par ville, code postal et option
     */
    #[Route('/stages', name: 'stages_get', methods: ['GET'])]
    public function index(StageRepository $unStageRepository, SerializerInterface $unSerialiseur, request $request, SpecialiteRepository $speRepo): JsonResponse
    {
        $ville = $request->query->get('ville') ?? '';
        $cp = $request->query->get('cp') ?? '';
        $opt = $request->query->get('option') ?? '';
        $easterEgg = $request->query->get('about') ?? '';
        $existOpt = $opt !== '' ? $speRepo->findOneBySigle($opt) : 'SLAM';
        if (empty($existOpt)) {
            $result = ["message" => "Filtrage impossible", "erreur" => "Option inexistante"];

            return new JsonResponse($result, JsonResponse::HTTP_NOT_FOUND, [], false);
        }
        if ($easterEgg === "teapot") {
            $result = ["message" => "Bravo ! Vous avez trouver l'Easter Egg !", "EasterEgg" => "00101110001011010010111000100000001011100010000000101101001011010010111000100000001011100010110100100000001011100010110100101110001000000010110100101110001011100010000000101110001000000010110100101101001011100010111000100000001011110010000000101110001011010010111000101110001000000010111000100000001011110010000000101101001011100010110100101110001000000010110100101101001011010010000000101101001011100010111000100000001011100010000000101111001000000010111000101110001011100010111000100000001011010010000000101101001000000010111000101101001011010010111000100000001011110010000000101101001011010010110100101110001011100010111000100000001011010010111000101110"];
            return new JsonResponse($result, 418, [], false);
        }

        if (empty($opt) && empty($cp) && empty($ville)) {
            $lesStages = $unStageRepository->findAll();
        } else {
            $lesStages = $unStageRepository->findByGetParam($opt, $ville, $cp);
        }
        $result = [
            'message' => 'OK',
            'data' => $lesStages,
        ];
        $serializedResult = $unSerialiseur->serialize($result, 'json', [AbstractNormalizer::GROUPS => ['stages.get']]);
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
        try {
            $data = $request->toArray();
            $organisationId = $data['idOrganisation'];
            $etudiantId = $data['idEtudiant'];
            $periodeId = $data['idPeriodeStage'];
            $Repo = [
                "Organisation" => $em->getRepository(Organisation::class)->find($organisationId),
                "Etudiant" => $em->getRepository(Etudiant::class)->find($etudiantId),
                "Periode" => $em->getRepository(Periode::class)->find($periodeId)
            ];
            if ($Repo["Organisation"] !== null && $Repo["Etudiant"] !== null && $Repo["Periode"] !== null) {
                $unStage = $unSerialiseur->deserialize($contenu, Stage::class, 'json');
                $errorsStage = $unValidator->validate($unStage);

                $messages = test($errorsStage);
                if (!empty($messages)) {

                    $result = ["message" => "Données erronées", "errors" => $messages];

                    return new JsonResponse($result, JsonResponse::HTTP_BAD_REQUEST, [], false);
                } else {
                    $unStage->setEtudiant($Repo["Etudiant"]);
                    $unStage->setOrganisation($Repo["Organisation"]);
                    $unStage->setPeriode($Repo["Periode"]);
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
            } else {
                if ($Repo["Organisation"] == null) {
                    $messages[] = "L'identifiant de Organisation est invalide";
                } else if ($Repo["Etudiant"] == null) {
                    $messages[] = "L'identifiant de Etudiant est invalide";
                } else {
                    $messages[] = "L'identifiant de Periode Stage est invalide";
                }

                $result = ["message" => "Données erronées", "errors" => $messages];

                return new JsonResponse($result, JsonResponse::HTTP_BAD_REQUEST, [], false);
            }
        } catch (Exception $e) {
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
