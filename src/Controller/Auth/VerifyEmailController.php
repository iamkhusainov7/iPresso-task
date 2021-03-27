<?php

namespace App\Controller\Auth;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class VerifyEmailController extends AbstractController
{
    private $verifyEmailHelper;

    public function __construct(VerifyEmailHelperInterface $helper)
    {
        $this->verifyEmailHelper = $helper;
    }

    /**
     * @Route("/verify", name="registration_confirmation_route")
     */
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository
    ) {
        $id = $request->get('id'); // retrieve the user id from the url

        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->json([
                'message' => 'User does not exist!'
            ]);
        }

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

            if ($user->getIsConfirmed()) {
                return $this->json([
                    'message' => 'The email has been already confirmed!'
                ], 200);
            }
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());
            return $this->json([
                'message' => $e->getReason()
            ], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user->setIsConfirmed(true);
        $user->setApiToken(sha1(random_bytes(20)));
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Your e-mail address has been verified.');

        return $this->json([
            'message' => 'Your email was sucessfully confirmed',
            'token'   => $user->getApiToken()
        ]);
    }
}
