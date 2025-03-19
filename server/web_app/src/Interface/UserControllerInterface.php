<?php
namespace App\Interface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface UserControllerInterface
{
    public function createUser(Request $req): Response;

    public function getAllUsers(Request $req): Response;

    public function getUserById(int $userId): Response;

    public function updateUser(int $userId, Request $req): Response;

    public function deleteUser(int $userId): Response;

    public function changePassword(int $userId, string $oldPassword, string $newPassword): Response;
}
