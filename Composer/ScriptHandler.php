<?php

namespace StadLine\StatusPageBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Dumper;

class ScriptHandler
{
    /** @var  string */
    private static $sfAppDir;

    /** @var integer */
    private static $yamlMode = 2;

    /**
     * Build the new version.
     *
     * @param Event $event
     */
    public static function buildVersion(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();
        self::$sfAppDir = $extras['symfony-app-dir'];

        $version = self::getProcessOutPut('git describe --tags');
        $commitHash = self::getProcessOutPut('git log --pretty=format:"%h" -n 1');
        $commitTag = self::getProcessOutPut('git describe --abbrev=0 --tags');

        self::runProcess(sprintf(
            'echo {"vcs": "git", "branch": "master", "hash": "%s", "tag": "%s"} > %s',
            $commitHash,
            $commitTag,
            self::$sfAppDir . '/../.git_version'
        ));

        self::createVersionFile($version);
    }

    /**
     * Get commit hash.
     *
     * @param string
     * @return string
     */
    private static function getProcessOutPut($cmd)
    {
        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * @param $cmd
     */
    private static function runProcess($cmd)
    {
        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Create a yaml version file.
     *
     * @param version
     */
    private static function createVersionFile($version)
    {
        $dumper = new Dumper();

        file_put_contents(self::$sfAppDir . '/config/version.yml', $dumper->dump(
            array('parameters' => array('assets_version' => $version)),
            self::$yamlMode
        ));
    }
}
