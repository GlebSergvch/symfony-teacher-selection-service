<?php

namespace App\Controller\Api\v1;

use App\Entity\Group;
use App\Manager\GroupManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/group')]
class GroupController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    public function __construct(private readonly GroupManager $groupManager)
    {
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveGroupAction(Request $request): Response
    {
        $groupName = $request->request->get('groupName');
        $minimumSize = $request->request->get('minimumSize');
        $maximumSize = $request->request->get('maximumSize');
        $group = $this->groupManager->create($groupName, $minimumSize, $maximumSize);
        [$data, $code] = $group === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'groupId' => $group->getId()], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getGroupAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $groups = $this->groupManager->getGroups($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($groups) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['groups' => array_map(static fn(Group $group) => $group->toArray(), $groups)], $code);
    }

    #[Route(path: '', methods: ['DELETE'])]
    public function deleteGroupAction(Request $request): Response
    {
        $groupId = $request->query->get('groupId');
        $result = $this->groupManager->deleteGroup($groupId);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateGroupAction(Request $request): Response
    {
        $groupId = $request->query->get('groupId');
        $name = $request->query->get('name');
        $result = $this->groupManager->updateGroup($groupId, $name);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}