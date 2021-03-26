<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Exceptions\Contracts\MultipleArgumentExceptionInterface;
use InvallidArgumentsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class UserRegistrationController extends AbstractController
{
    /**
     * @Route("/auth/user/registration", name="auth_user_registration")
     */
    public function register(Request $request, ValidatorInterface $validator)
    {
        try {
            $user = $this->create($request, $validator);

            return $this->json([
                'data' => [
                    'email' => $user->getEmail,
                    'name'  => $user->getName
                ],
                'message' => 'Success!'
            ]);
        } catch (MultipleArgumentExceptionInterface $e) {
            return $this->json([
                'messages' => $e->getMessages()
            ], 400);
        } catch (Throwable $e) {
            throw $e;
            return $this->json([
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    protected function create(Request $request, ValidatorInterface $validator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = [
            'name' => $request->get('firstname'),
            'surname' => $request->get('surname'),
            'email' => $request->get('email'),
            'bday' => $request->get('bday'),
            'phone_number' => $request->get('phone_number')
        ];

        $user = new User($data);

        $validation = $validator->validate($user);
        $errorMessages = [];

        if (count($validation) > 0) {
            foreach ($validation as $val) {
                $errorMessages[$val->getPropertyPath()] = $val->getMessage();
            }

            throw new InvallidArgumentsException(400, $errorMessages);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
}
