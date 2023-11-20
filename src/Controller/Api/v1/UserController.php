<?php

namespace App\Controller\Api\v1;

use App\Dto\ManageUserDTO;
use App\Dto\ManageUserProfileDTO;
use App\Entity\User;
use App\Form\Type\CreateUserType;
use App\Form\Type\UpdateUserProfileType;
use App\Form\Type\UpdateUserType;
use App\Manager\UserManager;
use App\Manager\UserProfileManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/user')]
class UserController extends AbstractController
{
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_PER_PAGE = 20;

    public function __construct(
        private readonly UserManager $userManager,
        private readonly UserProfileManager $userProfileManager,
        private readonly FormFactoryInterface $formFactory
    )
    {
    }

    #[Route(path: '', methods: ['GET'])]
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $users = $this->userManager->getUsers($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    }

    #[Route(path: '/find-by-user-profile', methods: ['POST'])]
    public function getUsersFindByUserProfile(Request $request)
    {
        $filter = $request->request->get('filter');
//        TODO findByUserProfile: добавить получение поля filter как вложенность:
//        {
//          "filter": {
//              "firstname": "john"
//                ...
//          }
//        }


//        filter должен возвращать вложенность
//        $filter['firstname'] = 'john';
//        $filter['lastname'] = 'doe';
        $users = $this->userManager->getUsersByUserProfile($filter,$page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    }

    #[Route(path: '/find-login/{user_login}', methods: ['GET'])]
    public function getUsersFindLoginAction(string $user_login): Response
    {
        $users = $this->userManager->getUsersByLogin($user_login,$page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    }

    #[Route(path: '/by-login/{user_login}', methods: ['GET'], priority: 2)]
    #[ParamConverter('user', options: ['mapping' => ['user_login' => 'login']])]
    public function getUserByLoginAction(User $user): Response
    {
        return new JsonResponse(['user' => $user->toArray()], Response::HTTP_OK);
    }

//    #[Route(path: '', methods: ['POST'])]
//    public function saveUserAction(Request $request): Response
//    {
//        $login = $request->request->get('login');
//        $role = $request->request->get('role');
//        $userId = $this->userManager->saveUser($login, $role);
//        [$data, $code] = $userId === null ?
//            [['success' => false], Response::HTTP_BAD_REQUEST] :
//            [['success' => true, 'userId' => $userId], Response::HTTP_OK];
//
//        return new JsonResponse($data, $code);
//    }

    #[Route(path: '', methods: ['POST'])]
    public function saveUserAction(Request $request): Response
    {
        $saveUserDTO = ManageUserDTO::fromRequest($request);
        $userId = $this->userManager->saveUserFromDTO(new User(), $saveUserDTO);
        [$data, $code] = $userId === null ?
            [['success' => false], Response::HTTP_BAD_REQUEST] :
            [['success' => true, 'userId' => $userId], Response::HTTP_OK];

        return new JsonResponse($data, $code);
    }

    #[Route(path: '', methods: ['DELETE'])]
    public function deleteUserAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $result = $this->userManager->deleteUser($userId);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteUserByIdAction(int $id): Response
    {
        $result = $this->userManager->deleteUser($id);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '', methods: ['PATCH'])]
    public function updateUserAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $login = $request->query->get('login');
        $result = $this->userManager->updateUser($userId, $login);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '/create-user-valid', name: 'create_user', methods: ['GET', 'POST'])]
    #[Route(path: '/update-user-valid/{id}', name: 'update_user', methods: ['GET', 'PATCH'])]
    public function manageUserAction(Request $request, string $_route, ?int $id = null): Response
    {
        if ($id) {
            $user = $this->userManager->getUserById($id);
            $dto = ManageUserDTO::fromEntity($user);
        }

        $form = $this->formFactory->create(
            $_route === 'create_user' ? CreateUserType::class : UpdateUserType::class,
            $dto ?? null,
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ManageUserDTO $userDto */
            $userDto = $form->getData();

            $this->userManager->saveUserFromDTO($user ?? new User(), $userDto);
        }

        return $this->renderForm('manageUser.html.twig', [
            'form' => $form,
            'isNew' => $_route === 'create-user-valid',
            'user' => $user ?? null,
        ]);
    }

    #[Route(path: '/update-user-profile-valid/{id}', name: 'update_user_profile', methods: ['GET', 'PATCH'])]
    public function updateUserProfileAction(Request $request, string $_route, int $id): Response
    {
        $userProfile = $this->userProfileManager->getUserProfileById($id);
        $dto = ManageUserProfileDTO::fromEntity($userProfile);
        $form = $this->formFactory->create(UpdateUserProfileType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ManageUserProfileDTO $userDto */
            $userDto = $form->getData();

            $this->userProfileManager->saveUserFromDTO($userProfile, $userDto);
        }

        return $this->renderForm('manageUser.html.twig', [
            'form' => $form,
            'isNew' => false,
            'user' => $userProfile ?? null,
        ]);
    }
}