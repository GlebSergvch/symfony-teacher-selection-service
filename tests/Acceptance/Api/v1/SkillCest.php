<?php

namespace AcceptanceTests\Api\v1;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class SkillCest
{

    private string $token;

    public function _before(AcceptanceTester $I): void
    {
//         Аутентификация пользователя
        $I->haveHttpHeader('Authorization', 'Basic ' . base64_encode('my_user:my_pass'));
        $tokenResponse = $I->sendPost('/api/v1/token', );
        $responseData = json_decode($tokenResponse, true);
        $this->token = $responseData['token'];
    }

    public function testCreateSkill(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $skillName = self::SKILL_DATA['create_skill_data']['name'];
        $I->sendPost('/api/v1/skill', ['name' => $skillName]);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['skillId' => 'integer:>0']);
    }

    public function testGetSkills(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $I->sendGet('/api/v1/skill', ['page' => 1, 'perPage' => 20]);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['skills' => 'array']);
    }

    public function testUpdateSkillById(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $skillName = self::SKILL_DATA['update_skill_data']['name'];
        $res = json_decode($I->sendPost('/api/v1/skill', ['name' => $skillName]), true);

        $skillName = $skillName . '_updated';
        $skillId = $res['skillId'];
        $I->sendPatch("/api/v1/skill?skillId=$skillId&name=$skillName");

        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['success' => 'boolean']);
        $I->canSeeResponseContainsJson(['success' => true]); // Изменение в этой строке
    }
//
    public function testDeleteSkill(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $skillName = self::SKILL_DATA['delete_skill_data']['name'];
        $res = json_decode($I->sendPost('/api/v1/skill', ['name' => $skillName]), true);

        $skillId = $res['skillId'];
        $I->sendDelete("/api/v1/skill?skillId=$skillId");

        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['success' => 'boolean']);
        $I->canSeeResponseContainsJson(['success' => true]); // Изменение в этой строке
    }

    const SKILL_DATA = [
        'create_skill_data' => [
            'name' => 'other_skill',
        ],
        'update_skill_data' => [
            'name' => 'update_skill',
        ],
        'delete_skill_data' => [
            'name' => 'delete_user',
        ],
    ];
}