<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/groups")
 */
class GroupController  extends BaseController
{
    /**
     * @Route("/", name="groups_index", methods={"GET"})
     */
    public function index(GroupRepository $groupRepository)
    {
        $groups = $groupRepository->findAll();
        $json = $this->serialize(['groups' => $groups]);

        return new Response($json, 200);
    }

    /**
     * @Route("/", name="groups_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        $json = $this->serialize($group);
        $response = new JsonResponse($json, 201);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{id}", name="groups_update", methods={"PUT", "PATCH"})
     */
    public function update(Request $request, GroupRepository $groupRepository, $id)
    {
        $group = $groupRepository->find($id);
        if (!$group) {
            throw $this->createNotFoundException(sprintf(
                'No group found with id "%s"',
                $id
            ));
        }

        $form = $this->createForm(GroupType::class, $group);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        $json = $this->serialize($group);
        $response = new Response($json, 200);

        return $response;
    }

    /**
     * @Route("/{id}", name="groups_delete", methods={"DELETE"})
     */
    public function delete(GroupRepository $groupRepository, $id)
    {
        $group = $groupRepository->find($id);

        if ($group) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();
        }

        return new Response(null, 204);
    }

    /**
     * @Route("/get-users/{id}", name="groups_delete", methods={"GET"})
     */
    public function getGroupUsers(GroupRepository $groupRepository, $id)
    {
        $group = $groupRepository->find($id);

        if (!$group) {
            throw $this->createNotFoundException(sprintf(
                'No group found with id "%s"',
                $id
            ));
        }

        $users = $group->getUsers();
        $json = $this->serialize(['users' => $users]);

        return new Response($json, 200);
    }
}
