<?php

namespace AcceptanceTests\Api\v1;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class UserCest
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

    public function testAddUserActionForAdmin(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $params = self::USERS_DATA['create_user_data'];
        $I->sendPost('/api/v1/user', $params);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['userId' => 'integer:>0']);
    }

    public function testGetUserByLogin(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $params = self::USERS_DATA['find_user_data'];
        $I->sendPost('/api/v1/user', $params);

        $login = $params['login'];
        $I->sendGet("/api/v1/user/find-login/$login");

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['users' => 'array']);
    }

    public function testUpdateByLogin(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $params = self::USERS_DATA['update_user_data'];
        $res = json_decode($I->sendPost('/api/v1/user', $params), true);

        $login = $params['login'] . '_updated';
        $userId = $res['userId'];
        $I->sendPatch("/api/v1/user?userId=$userId&login=$login");

        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['success' => 'boolean']);
        $I->canSeeResponseContainsJson(['success' => true]); // Изменение в этой строке
    }

    public function testDeleteUser(AcceptanceTester $I): void
    {
        $I->amBearerAuth($this->token);
        $params = self::USERS_DATA['delete_user_data'];
        $res = json_decode($I->sendPost('/api/v1/user', $params), true);

        $userId = $res['userId'];
        $I->sendDelete("/api/v1/user?userId=$userId");

        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['success' => 'boolean']);
        $I->canSeeResponseContainsJson(['success' => true]); // Изменение в этой строке
    }

    const USERS_DATA = [
        'create_user_data' => [
            'login' => 'other_user',
            'password' => 'other_password',
            'isActive' => 'true',
            'age' => 23,
        ],
        'find_user_data' => [
            'login' => 'find_user',
            'password' => 'other_password',
            'isActive' => 'true',
            'age' => 23,
        ],
        'update_user_data' => [
            'login' => 'updated_user',
            'password' => 'other_password',
            'isActive' => 'true',
            'age' => 23,
        ],
        'delete_user_data' => [
            'login' => 'delete_user',
            'password' => 'other_password',
            'isActive' => 'true',
            'age' => 23,
        ],
    ];
}