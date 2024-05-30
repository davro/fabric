<?php

namespace Fabric\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends Command
{
    protected static $defaultName = 'create:project';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new Fabric project.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectName = $input->getArgument('name');
        $directories = [
            "$projectName/app/Controllers",
            "$projectName/app/Models",
            "$projectName/app/Views/templates",
            "$projectName/app/Middleware",
            "$projectName/config",
            "$projectName/public",
            "$projectName/routes",
            "$projectName/src",
            "$projectName/storage/cache",
            "$projectName/storage/logs",
            "$projectName/tests"
        ];

        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                $output->writeln("Created directory: $dir");
            }
        }

        // Create initial files
        file_put_contents("$projectName/config/settings.php", "<?php\n\nreturn [\n    'settings' => [\n        'displayErrorDetails' => true,\n    ],\n];\n");
        file_put_contents("$projectName/routes/web.php", "<?php\n\nuse Slim\\Factory\\AppFactory;\n\n\$app = AppFactory::create();\n\n// Define app routes\n\nreturn \$app;\n");
        file_put_contents("$projectName/public/index.php", "<?php\n\nrequire __DIR__ . '/../vendor/autoload.php';\n\n\$settings = require __DIR__ . '/../config/settings.php';\n\n\$app = require __DIR__ . '/../routes/web.php';\n\n\$app->run();\n");

        $output->writeln("Project '$projectName' created successfully!");

        return Command::SUCCESS;
    }
}

