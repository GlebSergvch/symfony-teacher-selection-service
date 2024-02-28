<?php

namespace App\Consumer\UpdateSkill;

use App\Client\StatsdAPIClient;
use App\Consumer\UpdateSkill\Input\Message;
use App\Manager\SkillManager;
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
        private readonly SkillManager $skillManager,
        private readonly StatsdAPIClient $statsdAPIClient,
        private readonly string $key,
    ) {
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

        $skillId = $message->getSkillId();
        $skillNewName = $message->getSkillName();

        usleep(500000);

        $this->skillManager->updateSkill($skillId, $skillNewName);

        $this->statsdAPIClient->increment($this->key);
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