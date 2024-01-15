<?php

namespace App\Controller\Api\v1;

use App\Client\StatsdAPIClient;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Enum\UserRole;
use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;
use App\Service\TeacherSkillService;
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
        private readonly SkillManager $skillManager,
        private readonly TeacherSkillService $teacherSkillService,
        private readonly StatsdAPIClient $statsdAPIClient,
    )
    {
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveTeacherSkillAction(Request $request): Response
    {
        $studentId = $request->request->get('teacherId');
        $groupId = $request->request->get('skillId');

        $user = $this->userManager->getUserById($studentId);
        $skill = $this->skillManager->getSkillById($groupId);


        // TODO  через присваивание setStudentId и setGroupId в Entity/TeacherSkill для $studentId и $groupId работать не получатеся.
        // доктрина выдает ошибку и показывает эти поля как null.
        // приходится присваивать сущности напрямую.
        // в контексте доктрины так и нужно работать ?

        $teacherSkill = $this->teacherSkillManager->addTeacherSkill($user, $skill);
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

    #[Route(path: '/add-teacher-skill', methods: ['POST'])]
    public function addTeacherSkills(Request $request)
    {
        $this->statsdAPIClient->increment('add_teacher_skill');
        $data = json_decode($request->getContent(), true);
        $teachers = $data['users'];
        $skills = $data['skills'];


        $users = $this->teacherSkillService->addTeachersSkills($teachers, $skills);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    }
}