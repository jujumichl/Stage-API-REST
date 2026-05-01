<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Repository\OrganisationRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
             ], Response::HTTP_BAD_REQUEST);
         }
         $unContact = $unRepository->find($id);
         if (!$unContact) {
             $result = [
                 'message' => 'Ressource inexistante',
                 'data' => null
             ];
             $serializedResult = $unSerialiseur->serialize($result, 'json');
             return new JSONResponse($serializedResult, Response::HTTP_NOT_FOUND, [], true);
         } else {
             $result = [
                 'message' => 'OK',
                 'data' => $unContact
             ];
             $serializedResult = $unSerialiseur->serialize($result, 'json',  [AbstractNormalizer::IGNORED_ATTRIBUTES => ['civilite']]);
             return new JSONResponse($serializedResult, Response::HTTP_OK, [], true);
         }
     }

     // Chemin pour voir tous les contacts de l'organisation

     // Il doit être modifier le code pour avoir les contacts de l'orga
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
             ], Response::HTTP_BAD_REQUEST);
         }
         $uneOrganisation = $organisationRepository->find($id);
         if (!$uneOrganisation) {
             $result = [
                 'message' => 'Ressource inexistante',
                 'data' => null
             ];
             $serializedResult = $unSerialiseur->serialize($result, 'json');
             return new JSONResponse($serializedResult, Response::HTTP_NOT_FOUND, [], true);
         } else {
            $desContacts = $contactRepository->findBy(['organisation' => $uneOrganisation]);
             $result = [
                 'message' => 'OK',
                 'data' => $desContacts
             ];
             $serializedResult = $unSerialiseur->serialize($result, 'json',  [AbstractNormalizer::IGNORED_ATTRIBUTES => ['civilite']]);
             return new JSONResponse($serializedResult, Response::HTTP_OK, [], true);
         }
     }

    //  #[Route('/contacts', name: 'app_contact_add', methods: ['POST'])]
    //  public function addContact(
    //      SerializerInterface $unSerialiseur,
    //      ValidatorInterface $unValidateur,
    //      Request $request,
    //      EntityManagerInterface $em,
    //      ContactRepository $unRepository,
    //      URLGeneratorInterface $unUrlGenerateur
    //  ): JsonResponse {
    //      $data = $request->getContent();
    //      $prenom = $data['prenom'];
    //      $nom = $data['nom'];
    //      $email = $data['email'];
    //      $tel = $data['tel'];
    //      $fonction = $data['fonction'];
    //      $organisationId = $data['organisation_id'];
    //      $repo = [
    //          'prenom' => $em->getRepository(Organisation::class) -> find($organisationId),
    //          'nom' => $em->getRepository(Organisation::class) -> find($organisationId),
    //          'email' => $em->getRepository(Organisation::class) -> find($organisationId),
    //          'tel' => $em->getRepository(Organisation::class) -> find($organisationId),
    //          'fonction' => $em->getRepository(Organisation::class) -> find($organisationId),
    //          'organisation' => $em->getRepository(Organisation::class) -> find($organisationId)
    //      ];
    //      $unContact = $unSerialiseur->deserialize($data, Contact::class, 'json');
    //      $unContact->setNumeroOrganisation($repo['organisation']);        
    //      $em->persist($unContact);
    //      $em->flush();
    //      $location = $unUrlGenerateur->generate('app_contact_add', ['id' => $unContact->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
    //      $result = [
    //          'message' => "Contact d'id " . $unContact->getId() . " a été ajouté",
    //          '_selfLink' => $location
    //      ];
    //      return new JsonResponse($result, JsonResponse::HTTP_OK, [], true);
    //  }
 }
