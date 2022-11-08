<?php

namespace App\Controller;

use App\Enum\Group\BaseGroups;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RestAbstractController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator
    ) {}

    protected function objectResponse($object, int $code = Response::HTTP_OK, $context = []): Response
    {
        return new Response(
            $this->serializer->serialize(
                $object,
                JsonEncoder::FORMAT,
                $context
            ),
            $code,
            ['Content-Type' => 'application/json']
        );
    }

    protected function groups(array $groups): array {
        $groups[] = BaseGroups::DEFAULT;
        return [
            'groups' => $groups,
        ];
    }

    protected function validate($object, array $groups = []): void
    {
        $violations = $this->validator->validate($object, null, $groups);

        if ($violations->count() > 0) {
            throw new UnprocessableEntityHttpException($violations);
        }
    }
}