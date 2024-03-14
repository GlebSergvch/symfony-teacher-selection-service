<?php

namespace App\Command;

use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;
use App\Service\TeacherSkillService;
use JsonException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddTeacherSkillCommand extends Command
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly SkillManager $skillManager,
        private readonly TeacherSkillManager $teacherSkillManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('teacher_skill:add')
            ->setDescription('Add skill to teacher')
            ->addArgument('teacherId', InputArgument::REQUIRED, 'ID of teacher')
            ->addArgument('skillId', InputArgument::REQUIRED, 'ID of skill');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $teacherId = (int)$input->getArgument('teacherId');
            $skillId = (int)$input->getArgument('skillId');
            $teacher = $this->userManager->getUserById($teacherId);
            $skill = $this->skillManager->getSkillById($skillId);

            if ($teacher === null || $skill === null) {
                $output->write("<info>teacher or skill returned null</info>");
                return self::INVALID;
            }

            $result = $this->teacherSkillManager->addTeacherSkill($teacher, $skill);
            if ($result) {
                $output->write("<info>executing the command returned success</info>");
                return self::SUCCESS;
            }
        } catch (JsonException $e) {
            $output->write("<info>executing the command returned failure</info>");
            return self::FAILURE;
        }
        return self::INVALID;
    }
}