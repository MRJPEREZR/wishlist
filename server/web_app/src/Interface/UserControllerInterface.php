<?php
namespace App\Interface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface UserControllerInterface
{
    public function createUser(Request $req): Response;

    public function getAllUsers(): Response;

    public function getUserById(int $user_id): Response;

    public function updateUser(int $user_id, Request $req): Response;

    public function deleteUser(int $user_id): Response;

    public function authenticateUser(string $username, string $password): Response;

    public function changePassword(int $user_id, string $old_password, string $new_password): Response;
}
