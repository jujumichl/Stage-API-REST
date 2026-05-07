<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Organisation;
use App\Repository\ContactRepository;
use App\Repository\OrganisationRepository;

use Exception;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ContactController extends AbstractController
{
    // Chemin pour voir tous les contacts
    #[Route('/contacts', name: 'app_contact', methods: ['GET'])]
    public function index(SerializerInterface $unSerializer, ContactRepository $unRepository): JsonResponse
    {
        $lesContacts = $unRepository->findAll();
        $result = [
            'message' => 'OK',
            'data' => $lesContacts
        ];
        $serializedResult = $unSerializer->serialize($result, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['civilite']]);
        return new JsonResponse($serializedResult, Response::HTTP_OK, [], true);
    }

    // Chemin pour voir un contact selon l'ID
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
            $serializedResult = $unSerialiseur->serialize($result, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['civilite']]);
            return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
        }
    }

    // Chemin pour voir tous les contacts de l'organisation
    #[Route('/organisation/{id}/contacts', name: 'app_organisation_contacts', methods: ['GET'])]
    public function getContactsByOrganisation($id, SerializerInterface $unSerialiseur, OrganisationRepository $organisationRepository, ContactRepository $contactRepository, ValidatorInterface $unValidateur)
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
        $uneOrganisation = $organisationRepository->find($id);
        if (!$uneOrganisation) {
            $result = [
                'message' => 'Ressource inexistante',
                'data' => null
            ];
            $serializedResult = $unSerialiseur->serialize($result, 'json');
            return new JSONResponse($serializedResult, JsonResponse::HTTP_NOT_FOUND, [], true);
        } else {
            $desContacts = $contactRepository->findBy(['organisation' => $uneOrganisation]);
            $result = [
                'message' => 'OK',
                'data' => $desContacts
            ];
            $serializedResult = $unSerialiseur->serialize($result, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['civilite']]);
            return new JSONResponse($serializedResult, JsonResponse::HTTP_OK, [], true);
        }
    }

    private function test($errors)
{
    $messages = [];
    foreach ($errors as $error) {
        $messages[] = [$error->getPropertyPath() => $error->getMessage()];
    }
    return $messages;
}

    #[Route('/contact', name: 'app_contact_add', methods: ['POST'])]
    public function addContact(
        SerializerInterface $unSerialiseur, 
        EntityManagerInterface $em, 
        UrlGeneratorInterface $unUrlGenerateur, 
        Request $request, 
        ValidatorInterface $unValidator
        ): JsonResponse
    {
        $contenu = $request->getContent();
        try {
            $data = $request->toArray();
            $civilite = $data['civilite'];
            $nom = $data['nom'];
            $prenom = $data['prenom'];
            $email = $data['email'];
            $tel = $data['tel'];
            $fonction = $data['fonction'];
            $organisation_id = $data['organisation_id'];

            $Repo = [
                "civilite" => $civilite,
                "prenom" => $prenom,
                "nom" => $nom,
                "email" => $email,
                "tel" => $tel,
                "fonction" => $fonction,
                "Organisation" => $em->getRepository(Organisation::class)->find($organisation_id),
            ];

            if ($Repo["Organisation"] !== null) {
                $unContact = $unSerialiseur->deserialize($contenu, Contact::class, 'json');
                $errorsContact = $unValidator->validate($unContact);

                $messages = $this->test($errorsContact);
                if (!empty($messages)) {

                    $result = ["message" => "Données erronées", "errors" => $messages];

                    return new JsonResponse($result, JsonResponse::HTTP_BAD_REQUEST, [], false);
                } else {
                    $unContact->setCivilite($Repo['civilite']);
                    $unContact->setPrenom($Repo['prenom']);
                    $unContact->setNom($Repo['nom']);
                    $unContact->setEmail($Repo['email']);
                    $unContact->setTel($Repo['tel']);
                    $unContact->setFonction($Repo['fonction']);
                    $unContact->setNumeroOrganisation($Repo['Organisation']);
                    
                    $em->persist($unContact);

                    $em->flush();
                    $location = $unUrlGenerateur->generate(
                        'app_contact_add',
                        ['id' => $unContact->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );
                    $id = $unContact->getId();
                    $result = [
                        "message" => "Contact d'id {$id} créé",
                        "data" => [
                            "_selfLink" => $location
                        ]
                    ];
                    return new JsonResponse($result, JsonResponse::HTTP_CREATED, [], false);
                }
            } else {
                if ($Repo["Organisation"] == null) {
                    $messages[] = "L'identifiant de Organisation est invalide";
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
