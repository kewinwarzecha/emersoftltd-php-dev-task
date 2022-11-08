<?php

namespace App\Controller;


use App\Entity\User;
use App\Enum\Group\BaseGroups;
use App\Enum\Group\UserGroups;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

#[Route(path: '/api', name: 'api_user_')]
class UserController extends RestAbstractController
{
    /** Store new User */
    #[OA\Tag(name: 'users')]
    #[OA\RequestBody(required: true, content: new Model(type: User::class, groups: [UserGroups::CREATE]))]
    #[OA\Response(
        response: 201,
        description: 'created',
        content: new OA\JsonContent(
            ref: new Model(type: User::class, groups: [UserGroups::SHOW, BaseGroups::DEFAULT])
        ))]
    #[OA\Response(response: 422,description: "Unprocessable Content")]
    #[OA\Response(response: 500, description: 'Internal error')]
    #[Route(path: '/user/register', name: 'store', methods: 'POST')]
    public function store(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher): Response {

        try {
            /** @var User $user */
            $user = $this->serializer->deserialize(
                $request->getContent(),
                User::class,
                JsonEncoder::FORMAT,
                $this->groups([
                    UserGroups::CREATE
                ])
            );
        } catch (NotNormalizableValueException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        $this->validate($user);

        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $userRepository->save($user, true);

        return $this->objectResponse(
            $user,
            Response::HTTP_CREATED,
            $this->groups([UserGroups::SHOW])
        );
    }

    /** Patch existing User */
    #[OA\Tag(name: 'users')]
    #[OA\RequestBody(required: true, content: new Model(type: User::class, groups: [UserGroups::UPDATE]))]
    #[OA\Response(
        response: 200,
        description: 'updated',
        content: new OA\JsonContent(
            ref: new Model(type: User::class, groups: [UserGroups::SHOW, BaseGroups::DEFAULT])
        ))]
    #[OA\Response(response: 401,description: "Unauthorized")]
    #[OA\Response(response: 422,description: "Unprocessable Content")]
    #[OA\Response(response: 500, description: 'Internal error')]
    #[Route(path: '/user', name: 'update', methods: 'PATCH')]
    public function update(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher): Response {
        $loggedUser = $this->getUser();

        $requestContentRaw = $request->getContent();
        $requestContent = json_decode($requestContentRaw);

        try{
            /** @var User $user */
            $user = $this->serializer->deserialize(
                $requestContentRaw,
                User::class,
                JsonEncoder::FORMAT,
                [
                    'object_to_populate' => $loggedUser,
                    'groups' => UserGroups::UPDATE,
                ]
            );
        } catch (NotNormalizableValueException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        if (isset($requestContent->password)) {
            $hashedPassword = $passwordHasher->hashPassword($user, $requestContent->password);
            $user->setPassword($hashedPassword);
        }

        $this->validate($user);

        $userRepository->save($user, true);

        return $this->objectResponse($user, context: $this->groups([UserGroups::SHOW]));
    }

}