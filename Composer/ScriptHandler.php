<?php

namespace Stadline\StatusPageBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Dumper;

class ScriptHandler
{
    /** @var string */
    private static $sfAppDir;

    /** @var string */
    private static $commitHash;

    /** @var string */
    private static $commitTag;

    /** @var string */
    private static $branch;

    /** @var integer */
    private static $yamlMode = 2;

    /**
     * Build the new version.
     *
     * @param Event $event
     */
    public static function buildVersion(Event $event)
    {
        try {
            $extras = $event->getComposer()->getPackage()->getExtra();
            self::$sfAppDir = $extras['symfony-app-dir'];
            self::$commitHash = self::getProcessOutPut('git log --pretty=format:"%h" -n 1');
            // get commit tag if exists
            try {
                self::$commitTag = self::getProcessOutPut('git describe --abbrev=0 --tags');
            } catch (ProcessFailedException $e) {
                // check special case : no tags in repository
                if (strstr($e->getMessage(), "No names found")) {
                    // no tags found
                    self::$commitTag = "no tag";
                } else {
                    // other error
                    throw $e;
                }
            }
            self::$branch = self::getProcessOutPut('git rev-parse --abbrev-ref HEAD');

            self::createVersionFile();
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get commit hash.
     *
     * @param string $cmd
     * @return string
     * @throws ProcessFailedException
     */
    private static function getProcessOutPut($cmd)
    {
        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return trim($process->getOutput());
    }

    /**
     * Create a yaml version file.
     */
    private static function createVersionFile()
    {
        $dumper = new Dumper();
        $filename = self::$sfAppDir . '/config/version.yml';

        if (!file_exists($filename)) {
            fopen($filename, 'w+');
        }

        file_put_contents(self::$sfAppDir . '/config/version.yml', $dumper->dump(
            array('parameters' => array(
                'build_vcs'           => 'git',
                'build_commit_tag'    => self::$commitTag,
                'build_commit_hash'   => self::$commitHash,
                'build_commit_branch' => self::$branch
            )),
            self::$yamlMode
        ));
    }
}
