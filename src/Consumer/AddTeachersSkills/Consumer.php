<?php

namespace App\Consumer\AddTeachersSkills;

use App\Consumer\AddTeachersSkills\Input\Message;
use App\Manager\UserManager;
use App\Service\TeacherSkillService;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Consumer implements ConsumerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly TeacherSkillService $subscriptionService,
        private readonly UserManager $userManager
    )
    {
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = Message::createFromQueue($msg->getBody());
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }
        } catch (JsonException $e) {
            return $this->reject($e->getMessage());
        }

        $teachers = $message->getTeachers();
        $skills = $message->getSkills();

        if (!$teachers || !$skills) {
            return $this->reject(sprintf('empty values'));
        }

        $this->subscriptionService->addTeachersSkills($teachers, $skills);

//        foreach ($teachers as $teacher) {
//            $user = $this->userManager->findUserByLogin($teacher);
//            $skills = $user->getTeacherSkills();
//        }

        $this->entityManager->clear();
        $this->entityManager->getConnection()->close();

        return self::MSG_ACK;
    }

    private function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}