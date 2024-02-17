<?php

namespace App\Controller\Api\v1;

use App\Client\StatsdAPIClient;
use App\Dto\SkillDto;
use App\Entity\Skill;
use App\Manager\SkillManager;
use App\Service\AsyncService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/skill')]
class SkillController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    public function __construct(
        private readonly SkillManager $skillManager,
        private readonly StatsdAPIClient $statsdAPIClient,
        private readonly AsyncService $asyncService,
    )
    {
    }

    #[Route(path: '', methods: ['POST'])]
    public function saveSkillAction(Request $request): Response
    {
        $skillName = $request->request->get('name');
        $skillId = $this->skillManager->saveSkill($skillName)->getId();
        [$data, $code] = $skillId === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'userId' => $skillId], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateSkillAction(Request $request): Response
    {
        $skillId = $request->query->get('skillId');
        $name = $request->query->get('name');
        $result = $this->skillManager->updateSkill($skillId, $name);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getSkillAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $skills = $this->skillManager->getSkills($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($skills) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['skills' => array_map(static fn(Skill $skill) => $skill->toArray(), $skills)], $code);
    }

    #[Route(path: '', methods: ['DELETE'])]
    public function deleteSkillAction(Request $request): Response
    {
        $skillId = $request->query->get('skillId');
        $result = $this->skillManager->deleteSkill($skillId);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '/update-skill-async', methods: ['PATCH'])]
    public function updateSkillAsync(Request $request)
    {
        $this->statsdAPIClient->increment(AsyncService::UPDATE_SKILL);
        $skillId = $request->query->get('skillId');
        $skillName = $request->query->get('name');

        $messageSkill = (new SkillDto($skillId, $skillName))->toAMQPMessage();
        $result = $this->asyncService->publishToExchange(AsyncService::UPDATE_SKILL, $messageSkill);

        return new JsonResponse($result, 200);
    }
}