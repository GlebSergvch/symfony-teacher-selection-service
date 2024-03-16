<?php

namespace AcceptanceTests\Api\v1;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class UserCest
{
    public function testAddUserActionForAdmin(AcceptanceTester $I): void
    {
        $I->amAdmin();

        $tokenResponse = $I->sendPost('/api/v1/token');
        $responseData = json_decode($tokenResponse, true);
        $token = $responseData['token'];

//        dd($token);

        // Отправка запроса с токеном в заголовке Authorization
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);

        // Устанавливаем заголовок Authorization
        $I->amBearerAuthenticated($token);

        $params = $this->getAddUserParams();
//        dd($authorizationHeader); die();


//        $I->haveHttpHeader('Host', 'www.wikipedia.org');
//        $I->haveHttpHeader('Host', "<calculated when request is sent>");
//        $hostHeader = $I->grabHttpHeader('Host');


        $I->sendPost('/api/v1/user', $params);

        // Проверяем успешность запроса
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['id' => 'integer:>0']);
    }

    private function getAddUserParams(): array
    {
        return [
            'login' => 'other_user',
            'password' => 'other_password',
//            'roles' => '["ROLE_USER"]',
            'age' => 23,
            'isActive' => 'true',
        ];
    }

//    private function authenticateUserAndGetToken(AcceptanceTester $I): string
//    {
//        // Отправка запроса для аутентификации и получения токена
//        $I->sendPOST('/api/v1/token');
//
//        // Проверка успешности аутентификации
//        $I->canSeeResponseCodeIs(HttpCode::OK);
//        $response = json_decode($I->grabResponse(), true);
//
//        // Проверка наличия токена в ответе
//        if (!isset($response['token'])) {
//            throw new \RuntimeException('Token not found in response');
//        }
//
//        // Возвращаем токен
//        return $response['token'];
//    }
}