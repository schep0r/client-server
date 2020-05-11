<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController extends BaseController
{
    /**
     * @Route("/", name="users_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        $json = $this->serialize(['users' => $users]);

        $response = new Response($json, 200);

        return $response;
    }

    /**
     * @Route("/", name="users_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $json = $this->serialize($user);
        $response = new JsonResponse($json, 201);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{id}", name="users_update", methods={"PUT", "PATCH"})
     */
    public function update(Request $request, UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException(sprintf(
                'No user found with id "%s"',
                $id
            ));
        }

        $form = $this->createForm(UserType::class, $user);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $json = $this->serialize($user);
        $response = new Response($json, 200);

        return $response;
    }

    /**
     * @Route("/{id}", name="users_delete", methods={"DELETE"})
     */
    public function delete(UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);

        if ($user) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return new Response(null, 204);
    }
}
