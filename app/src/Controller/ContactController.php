<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ContactRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ContactController extends AbstractController
{
    #[Route('/contacts', name: 'app_contact', methods: ['GET'])]
    public function index(SerializerInterface $unSerializer, ContactRepository $unRepository): JsonResponse
    {
        $lesContacts = $unRepository->findAll();
        $result = [
            'message' => 'OK',
            'data' => $lesContacts
        ];
        $serializedResult = $unSerializer->serialize($result, 'json');
        return new JsonResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
    }
    #[Route('/contacts/{id}', name: 'app_contact_id', methods: ['GET'])]
    public function getContactId($id, SerializerInterface $unSerialiseur, ContactRepository $unRepository, ValidatorInterface $unValidateur)
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Regex(['pattern' => '/^[0-9]{1,8}$/', 'message' => "L'id doit comporter au minimum un et au maximum huit chiffres"]),
        ];
        $errors = $unValidateur->validate($id, $constraints);
        if ($errors->count() > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            return new JsonResponse([
                'message' => 'Données erronées',
                'errors' => $messages
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $unContact = $unRepository->find($id);
        if (!$unContact) {
            $result = [
                'message' => 'Ressource inexistante',
                'data' => null
            ];
            $serializedResult = $unSerialiseur->serialize($result, 'json');
            return new JSONResponse($serializedResult, JsonResponse::HTTP_NOT_FOUND, [], true);
        } else {
            $result = [
                'message' => 'OK',
                'data' => $unContact
            ];
            $serializedResult = $unSerialiseur->serialize($result, 'json');
            return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
        }
    }
}
