<?php

namespace App\Command;

use App\Entity\CsvFile;
use App\Entity\User;
use App\Repository\CsvFileRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'users:export',
    description: 'Export users to .csv file',
)]
class UsersExportCommand extends Command
{
    private string $filesDir;

    public function __construct(private readonly CsvFileRepository $csvFileRepository, private readonly UserRepository $userRepository, KernelInterface $kernel, string $name = null)
    {
        $this->filesDir = $kernel->getProjectDir() . '/public/files/';
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        $created = new \DateTime();
        $fileName = 'users' . $created->getTimestamp() . '.csv';
        $filePath = $this->filesDir . $fileName;

        $file = fopen($filePath, 'w');

        $rowData = [
            'id',
            'created_at',
            'modified_at',
            'email',
            'roles',
            'first_name',
            'last_name',
        ];
        
        fputcsv($file, $rowData);

        foreach ($users as $user) {
            $row = [
                $user->getId(),
                $user->getCreatedAt()?->format('Y-m-d H:i:s') ?? '-',
                $user->getModifiedAt()?->format('Y-m-d H:i:s') ?? '-',
                $user->getEmail(),
                implode(',',$user->getRoles()),
                $user->getFirstName(),
                $user->getLastName(),
            ];

            fputcsv($file, $row);
        }

        fclose($file);

        $fileEntity = new CsvFile();
        $fileEntity->setPath($filePath);

        $this->csvFileRepository->save($fileEntity, true);

        $io = new SymfonyStyle($input, $output);
        $io->success('The list has been saved in the file: ' . $filePath);

        return Command::SUCCESS;
    }
}
