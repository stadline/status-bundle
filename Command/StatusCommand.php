<?php

namespace Stadline\StatusPageBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;

class StatusCommand extends ContainerAwareCommand
{

    const MESSAGE_LENGTH = 70;

    private $collections;
    private $displayAll;

    protected function configure()
    {
        $this->setName('stadline:requirements:status')
                ->setDescription('Check application requirements')
                ->addOption('--display-all', null, InputOption::VALUE_NONE, 'Does not truncate output')
                ->addOption('--ignore-warnings', null, InputOption::VALUE_NONE, 'Ignore warnings for exit code')
                ->addOption('--names', null, InputOption::VALUE_NONE, 'Display collection names')
                ->addOption('--collection', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Collections to display');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->displayAll = $input->getOption('display-all');
        $this->collections = $this->getContainer()->get('stadline_status_page.requirement.collections');

        $this->displayNames($input, $output);
        $this->filterCollections($input, $output);
        $this->displayCollections($input, $output);

        return (int) $this->collections->hasIssue($input->getOption('ignore-warnings'));
    }

    /**
     * Display collections as a table
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function displayCollections(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['Collection', 'Pass?', 'Test message', 'Test info']);

        foreach ($this->collections as $collection) {
            $requirements = $collection->getRequirements();
            $recommendations = $collection->getRecommendations();

            foreach ($requirements as $requirement) {
                $rowData = $this->generateRowData($collection->getName(), $requirement);
                $table->addRow($rowData);
            }
            foreach ($recommendations as $recommendation) {
                $rowData = $this->generateRowData($collection->getName(), $recommendation);
                $table->addRow($rowData);
            }
        }

        $table->render();
    }

    private function generateRowData($collectionName, $test)
    {
        return [
            $collectionName,
            $test->isFulfilled() ? '<info>✔</info>' : '<error>✘</error>',
            $this->truncate($test->getTestMessage(), static::MESSAGE_LENGTH),
            $this->truncate($test->getHelpText(), static::MESSAGE_LENGTH),
        ];
    }

    private function truncate($text, $length)
    {
        if ($this->displayAll) {
            return $text;
        }
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }

    /**
     * Display collection names
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function displayNames(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('names')) {
            foreach ($this->collections as $collection) {
                $output->writeln($collection->getName());
            }
            exit;
        }
    }

    /**
     * Filter the collection by names
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function filterCollections(InputInterface $input, OutputInterface $output)
    {
        if ($collections = $input->getOption('collection')) {
            $this->collections = $this->collections->filter($collections);
        }
    }
}