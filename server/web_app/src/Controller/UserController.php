<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use App\Interface\UserControllerInterface;
use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

#[Route('/users')]
final class UserController extends AbstractController implements UserControllerInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    #[Route('', methods: ['POST'])]
    public function createUser(Request $req): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $user = new User();
        $user->setUserName($data['userName'] ?? null);
        $user->setName($data['name'] ?? null);
        $user->setSurname($data['surname'] ?? null);
        $user->setEmail($data['email'] ?? null); 
        $user->setCreatedAt(new \DateTimeImmutable());

        if (isset($data['password'])) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        }

        if (isset($data['role'])) { 
            try {
                $user->setRole(UserRole::from($data['role']));
            } catch (\ValueError $e) {
                return new JsonResponse(['error' => 'Invalid role value. Allowed values: admin, user'], Response::HTTP_BAD_REQUEST);
            }
        }else {
            $user->setRole(UserRole::from('user'));
        }

        if (isset($data['isBlocked'])) {
            if (!is_bool($data['isBlocked']) ) {
                return new JsonResponse(['error' => 'Invalid value for isBlocked. Allowed type boolean: true, false'], Response::HTTP_BAD_REQUEST);
            }
            $user->setIsBlocked($data['isBlocked']);
        }

        // Validate the User entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'This username or email is already registered.'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'User created successfully',
            'path' => 'src/Controller/UserController.php',
        ], Response::HTTP_CREATED);
    }

    #[Route('', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->userRepository->findAllUsers();
        $userArray = array_map(fn($user) => ['id' => $user->getId(), 'username' => $user->getUserName(), 'mail' => $user->getEmail(), 'createdAt' => $user->getCreatedAt()], $users);

        return $this->json([
            'users' => $userArray,
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/{userId}', methods: ['GET'])]
    public function getUserById(int $userId): JsonResponse
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $userDTO = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'mail' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()    
        ];
        return $this->json([
            'user' => $userDTO,
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/{userId}', methods: ['PATCH'])]
    public function updateUser(int $userId, Request $req): JsonResponse
    {   
        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    
        $data = json_decode($req->getContent(), true);
    
        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
    
        if (isset($data['username'])) {
            $user->setUserName($data['username']);
        }
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['surname'])) {
            $user->setSurname($data['surname']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['isBlocked'])) {
            if (!is_bool($data['isBlocked']) ) {
                return new JsonResponse(['error' => 'Invalid value for isBlocked. Allowed type boolean: true, false'], Response::HTTP_BAD_REQUEST);
            }
            $user->setIsBlocked($data['isBlocked']);
        }
        if (isset($data['role'])) {
            try {
                $user->setRole(UserRole::from($data['role']));
            } catch (\ValueError $e) {
                return new JsonResponse(['error' => 'Invalid role value. Allowed values: admin, user'], Response::HTTP_BAD_REQUEST);
            }
        }
    
        // Validate the user entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }
    
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) { 
            return new JsonResponse(['error' => 'This email is already registered.'], Response::HTTP_BAD_REQUEST);
        }
    
        return new JsonResponse([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUserName(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'isBlocked' => $user->isBlocked(),
                'role' => $user->getRole()->value,
            ],
            'path' => 'src/Controller/UserController.php'
        ], Response::HTTP_OK);
    }

    #[Route('/{userId}', methods: ['DELETE'])]
    public function deleteUser(int $userId): JsonResponse
    {
        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'User deleted successfully',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/authenticate', methods: ['POST'])]
    public function authenticateUser(string $username, string $password): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/authenticate/changePassword', methods: ['POST'])]
    public function changePassword(int $userId, string $oldPassword, string $newPassword): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}
