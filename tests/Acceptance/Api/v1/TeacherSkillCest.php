<?php

namespace AcceptanceTests\Api\v1;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class TeacherSkillCest
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

    public function testTeacherSkillCreateT(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $teacherId = 90;
        $skillId = 5;
        $I->sendPost('/api/v1/teacher-skill', ['teacherId' => $teacherId, 'skillId' => $skillId]);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['success' => 'boolean']);
        $I->canSeeResponseContainsJson(['success' => true]);
    }
}