<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Exceptions\Contracts\MultipleArgumentExceptionInterface;
use App\Service\CheckCurrencyUpdateService;
use InvallidArgumentsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class CurrencyController extends AbstractController
{
    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/currency/create", name="currency_creation", methods={"POST"})
     */
    public function create(Request $request, ValidatorInterface $validator, UserInterface $user)
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $data = [
                'min' => $request->get('min'),
                'max' => $request->get('max'),
                'user' => $request->get('user_id'),
                'currency' => $request->get('currency-name')
            ];

            $subcribtion = (new Subscription($data))->setUser($user);

            $validation = $validator->validate($subcribtion);
            $errorMessages = [];

            if (count($validation) > 0) {
                foreach ($validation as $val) {
                    $errorMessages[$val->getPropertyPath()] = $val->getMessage();
                }

                throw new InvallidArgumentsException(400, $errorMessages);
            }

            $entityManager->persist($subcribtion);
            $entityManager->flush();

            return $this->json([
                'message' => 'Success!'
            ]);
        } catch (MultipleArgumentExceptionInterface $e) {
            return $this->json([
                'messages' => $e->getMessages()
            ], 400);
        } catch (Throwable $e) {
            return $this->json([
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}
