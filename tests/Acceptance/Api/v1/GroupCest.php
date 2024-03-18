<?php

namespace AcceptanceTests\Api\v1;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class GroupCest
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

    public function testCreateGroup(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $params = self::GROUP_DATA['create_group_data'];
        $res = $I->sendPost('/api/v1/group', $params);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['groupId' => 'integer:>0']);
    }

    public function testGetGroups(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $I->sendGet('/api/v1/group', ['page' => 0, 'perPage' => 20]);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['groups' => 'array']);
    }

    public function testGetGroupsNoContent(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $I->sendGet('/api/v1/group', ['page' => 100000, 'perPage' => 20]);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function testUpdateGroupById(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $params = self::GROUP_DATA['update_group_data'];
        $res = json_decode($I->sendPost('/api/v1/group', $params), true);
        $groupNameUpdate = self::GROUP_DATA['update_group_data']['groupName'] . '_updated';
        $groupId = $res['groupId'];
        $I->sendPatch("/api/v1/group?groupId=$groupId&name=$groupNameUpdate");

        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['success' => 'boolean']);
        $I->canSeeResponseContainsJson(['success' => true]); // Изменение в этой строке
    }

    public function testDeleteGroup(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $params = self::GROUP_DATA['update_group_data'];
        $res = json_decode($I->sendPost('/api/v1/group', $params), true);
        $groupId = $res['groupId'];
        $I->sendDelete("/api/v1/group?groupId=$groupId");

        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['success' => 'boolean']);
        $I->canSeeResponseContainsJson(['success' => true]); // Изменение в этой строке
    }

    const GROUP_DATA = [
        'create_group_data' => [
            'groupName' => 'other_group',
            'minimumSize' => 10,
            'maximumSize' => 15,
        ],
        'update_group_data' => [
            'groupName' => 'update_group',
            'minimumSize' => 10,
            'maximumSize' => 15,
        ],
        'delete_group_data' => [
            'groupName' => 'delete_group',
            'minimumSize' => 10,
            'maximumSize' => 15,
        ],
    ];
}