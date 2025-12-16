<?php

namespace App\Controller;

use App\Entity\Organisation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\OrganisationRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final class OrganisationsController extends AbstractController
{
    /**
     * Fonction pour récupérer toutes les organisations
     * @return JsonResponse
     */
    #[Route('/organisations', name: 'app_organisations', methods: ['GET'])]
    public function all(OrganisationRepository $unRepository, SerializerInterface $unSerialiseur): JsonResponse
    {
        $lesOrganisations = $unRepository->findAll(); // Récupère toutes les organisations sous forme d'objets de l'entité Organisation
        $result = [
            'message' => 'OK',
            'data' => $lesOrganisations
        ];
        $serializedResult = $unSerialiseur->serialize($result, 'json');
        return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
    }
    /**
     * Fonction pour récupérer une organisation par son ID
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/organisations/{id}', name: 'app_organisation_by_id', methods: ['GET'])]
    public function getById(string $id, OrganisationRepository $unRepository, SerializerInterface $unSerialiseur, ValidatorInterface $unValidateur): JsonResponse
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Regex(['pattern' => '/^[0-9]{1,8}$/', 'message' => "L'id doit comporter au minimum un et au maximum huit chiffres"]),
        ];
        // Vérification d'un id string 
        $errors = $unValidateur->validate($id, $constraints);
        if ($errors->count() > 0) { // Vérifie s'il y a des erreurs de validation
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage(); // Parcourt les erreurs et récupère les messages
            }
            return new JsonResponse([
                'message' => 'Données erronées',
                'errors' => $messages
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $uneOrganisation = $unRepository->find($id); // Récupère l'organisation par son ID
        if (!$uneOrganisation) {
            $result = [
                'message' => 'Ressource inexistante',
                'data' => null
            ];
            $serializedResult = $unSerialiseur->serialize($result, 'json');
            return new JSONResponse($serializedResult, JsonResponse::HTTP_NOT_FOUND, [], true);
        } else {
            $result = [
                'message' => 'OK',
                'data' => $uneOrganisation
            ];
            $serializedResult = $unSerialiseur->serialize($result, 'json');
            return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
        }
    }
    /**
     * Fonction pour mettre à jour une organisation par son ID
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/organisations/{id}', name: 'app_organisation_maj', methods: ['PUT'])]
    public function update(string $id, Request $request, SerializerInterface $unSerialiseur, EntityManagerInterface $em, URLGeneratorInterface $unUrlGenerateur, ValidatorInterface $unValidateur)
    {
        // Vérification d'un id string 
        $errors = $unValidateur->validate($id, [
            new Assert\NotBlank(),
            new Assert\Regex(['pattern' => '/^[0-9]{1,8}$/', 'message' => "L'id doit comporter au minimum un et au maximum huit chiffres"]),
        ]);
        if ($errors->count() > 0) { // Vérifie s'il y a des erreurs de validation
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage(); // Parcourt les erreurs et récupère les messages
            }
            return new JsonResponse([
                'message' => 'Données erronées',
                'errors' => $messages
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $uneOrganisation = $em->getRepository(Organisation::class)->find($id); // Récupère l'organisation par son ID
        if (!$uneOrganisation) {
            $result = [
                'message' => 'Ressource inexistante',
                'data' => null
            ];
            $serializedResult = $unSerialiseur->serialize($result, 'json');
            return new JSONResponse($serializedResult, JsonResponse::HTTP_NOT_FOUND, [], true);
        }
        $data = $request->getContent();
        $unSerialiseur->deserialize($data, Organisation::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $uneOrganisation]);
        $em->persist($uneOrganisation);
        $em->flush();
        $location = $unUrlGenerateur->generate('app_organisation_maj', ['id' => $uneOrganisation->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $result = [
            'message' => "Organisation d'id " . $id . " a été modifiée",
            'data' => $uneOrganisation, 
            '_selfLink' => $location 
        ];
        return new JsonResponse($result, JsonResponse::HTTP_OK, ['Location' => $location], false);
    }
}
