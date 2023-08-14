<?php

namespace App\Controller\Api\v1;

use App\Entity\StudentGroup;
use App\Manager\GroupManager;
use App\Manager\StudentGroupManager;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/student-group')]
class StudentGroupController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    public function __construct(
        private readonly StudentGroupManager $studentGroupManager,
        private readonly UserManager $userManager,
        private readonly GroupManager $groupManager
    )
    {
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveSkillAction(Request $request): Response
    {
        $studentId = $request->request->get('studentId');
        $groupId = $request->request->get('groupId');

        $user = $this->userManager->getUserById($studentId);
        $group = $this->groupManager->getGroupById($groupId);

        $studentGroup = $this->studentGroupManager->addStudentGroup($user, $group);
        [$data, $code] = $studentGroup === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'userId' => $studentGroup], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getStudentGroupAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $studentGroups = $this->studentGroupManager->getStudentGroup($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($studentGroups) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['studentGroups' => array_map(static fn(StudentGroup $studentGroup) => $studentGroup->toArray(), $studentGroups)], $code);
    }

    #[Route(path: '', methods: ['DELETE'])]
    public function deleteSkillAction(Request $request): Response
    {
        $studentId = $request->query->get('studentId');
        $groupId = $request->query->get('groupId');

        $result = $this->studentGroupManager->deleteStudentGroup($studentId, $groupId);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}