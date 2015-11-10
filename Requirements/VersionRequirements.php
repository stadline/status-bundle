<?php

namespace StadLine\StatusPageBundle\Requirements;

use StadLine\StatusPageBundle\Requirements\RequirementCollection;

class VersionRequirements extends RequirementCollection
{
    public function __construct($container)
    {
        $tag = $container->getParameter('build_commit_tag');
        $hash = $container->getParameter('build_commit_hash');
        $branch = $container->getParameter('build_commit_branch');

        $this->addRequirement(!is_null($tag), "Git commit tag", $tag);
        $this->addRequirement(!is_null($hash), "Git commit hash", $hash);
        $this->addRequirement(!is_null($branch), "Git Branch", $branch);
    }

    public function getName()
    {
        return "Version";
    }

    public function getJsonData($file)
    {
        $git_version = '';
        if (file_exists($file)) {
            $git_version_handle = fopen($file, 'r');
            if ($git_version_handle != false) {
                $git_version = fread($git_version_handle, filesize($file));
                fclose($git_version_handle);
            }
        }
        return json_decode($git_version, true);
    }

}

