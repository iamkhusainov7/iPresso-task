<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Entity\Subscription;
use App\Events\UserRegistratedEvent;
use App\Exceptions\Contracts\MultipleArgumentExceptionInterface;
use InvallidArgumentsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Throwable;
use UserRegisteredListener;

class UserRegistrationController extends AbstractController
{
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer
    ) {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/auth/user/registration", name="auth_user_registration", methods={"POST"})
     */
    public function register(Request $request, ValidatorInterface $validator)
    {
        try {
            $user = $this->create($request, $validator);
            (new UserRegistratedEvent(
                new UserRegisteredListener(
                    $user,
                    $this->verifyEmailHelper,
                    $this->mailer
                )
            ))->notify();

            return $this->json([
                'data' => [
                    'email' => $user->getEmail(),
                    'name'  => $user->getName()
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

        $this->subscribeCurrencies(
            $user,
            $request->get('currencies'),
            $validator
        );
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    protected function subscribeCurrencies(
        User &$user,
        $currencies,
        ValidatorInterface $validator
    ) {
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($currencies as $key => $cur) {
            $data = [
                'min' => $cur['min'],
                'max' => $cur['max'],
                'currency-name' => $cur['currency-name']
            ];

            $subcribtion = new Subscription($data);
            $subcribtion->setUser($user);

            $validation = $validator->validate($subcribtion);
            $errorMessages = [];

            if (count($validation) > 0) {
                foreach ($validation as $val) {
                    $errorMessages["{$val->getPropertyPath()}[{$key}]"] = $val->getMessage();
                }

                throw new InvallidArgumentsException(400, $errorMessages);
            }

            $user->addSubscription($subcribtion);
            $entityManager->persist($subcribtion);
        }

        return true;
    }
}
