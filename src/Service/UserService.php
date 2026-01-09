<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserSignUpDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function registerUser(UserSignUpDto $dto): User
    {
        if ($this->userRepository->findOneBy(['email' => $dto->email])) {
            throw new ConflictHttpException('User with this email already exists');
        }

        $user = new User();
        $user->setEmail($dto->email);
        $user->setName($dto->name);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
