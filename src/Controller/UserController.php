<?php

namespace App\Controller;

use App\Model\UserSignUpDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    #[Route('/sign-up', name: 'app_user_signup', methods: ['POST'])]
    public function signUp(
        #[MapRequestPayload] UserSignUpDto $userSignUpDto,
    ): Response {
        $this->userService->registerUser($userSignUpDto);

        return new Response(null, Response::HTTP_CREATED);
    }

    #[Route('/sign-in', name: 'app_user_signin', methods: ['POST'])]
    public function signIn(): void
    {
        throw new \LogicException('This method should not be reached!');
    }
}
