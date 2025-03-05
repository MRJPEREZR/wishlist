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
        $user->setRole(UserRole::from($data['role'] ?? 'ROLE_USER')); 
        $user->setCreatedAt(new \DateTimeImmutable());

        if (isset($data['password'])) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
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

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'User created successfully',
            'path' => 'src/Controller/UserController.php',
        ], Response::HTTP_CREATED);
    }

    #[Route('', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->userRepository->findAllUsers();
        $userArray = array_map(fn($user) => ['id' => $user->getId(), 'username' => $user->getUsername()], $users);

        return $this->json([
            'users' => $userArray,
            'path' => 'src/Controller/ItemController.php',
        ]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getUserById(int $userId): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ItemController.php',
        ]);
    }

    #[Route('/{id}', methods: ['UPDATE'])]
    public function updateUser(int $userId, Request $req): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ItemController.php',
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteUser(int $userId): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ItemController.php',
        ]);
    }

    #[Route('/authenticate', methods: ['POST'])]
    public function authenticateUser(string $username, string $password): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ItemController.php',
        ]);
    }

    #[Route('/authenticate/changePassword', methods: ['POST'])]
    public function changePassword(int $userId, string $oldPassword, string $newPassword): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ItemController.php',
        ]);
    }
}
