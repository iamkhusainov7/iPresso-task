<?php

namespace App\Command;

use App\Services\CheckCurrencyUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Throwable;

class CheckCurrencyUpdateCommand extends Command
{
    protected static $defaultName = 'app:check_currency';
    protected $helper;
    protected $entityManager;
    protected $mailer;
    private SymfonyStyle $io;

    public function __construct(
        MailerInterface $mailer,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            CheckCurrencyUpdateService::check($this->entityManager, $this->mailer);

            $this->io->success("It was successfully executed!");
        } catch (Throwable $e) {
            throw $e;
            $this->io->error($e->getMessage());
        }

        return 0;
    }    
}
