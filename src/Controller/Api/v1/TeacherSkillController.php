<?php

namespace App\Controller\Api\v1;

use App\Entity\TeacherSkill;
use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/teacher-skill')]
class TeacherSkillController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    public function __construct(
        private readonly TeacherSkillManager $teacherSkillManager,
        private readonly UserManager $userManager,
        private readonly SkillManager $skillManager
    )
    {
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveSkillAction(Request $request): Response
    {
        $studentId = $request->request->get('teacherId');
        $groupId = $request->request->get('skillId');

        $user = $this->userManager->getUserById($studentId);
        $group = $this->skillManager->getSkillById($groupId);

        $teacherSkill = $this->teacherSkillManager->addTeacherSkill($user, $group);
        [$data, $code] = $teacherSkill === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'userId' => $teacherSkill], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getTeacherSkillAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $teacherSkills = $this->teacherSkillManager->getTeacherSkill($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($teacherSkills) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['teacherSkills' => array_map(static fn(TeacherSkill $teacherSkill) => $teacherSkill->toArray(), $teacherSkills)], $code);
    }

    #[Route(path: '', methods: ['DELETE'])]
    public function deleteTeacherAction(Request $request): Response
    {
        $studentId = $request->query->get('teacherId');
        $groupId = $request->query->get('skillId');

        $result = $this->teacherSkillManager->deleteTeacherSkill($studentId, $groupId);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
}