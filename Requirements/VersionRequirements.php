<?php

    namespace StadLine\StatusPageBundle\Requirements;

    use StadLine\StatusPageBundle\Requirements\RequirementCollection;

    class VersionRequirements extends RequirementCollection
    {
        public function __construct($rootDir, $git_version_file)
        {
            $data = $this->getJsonData($rootDir.'/../'.$git_version_file);

            $this->addRequirement(
                isset($data['hash']),
                "Last GIT Commit HASH", isset($data['hash']) ? $data['hash'] : 'NONE'
            );
            $this->addRequirement(
                isset($data['tag']),
                "Last GIT TAG", isset($data['tag']) ? $data['tag'] : 'NONE'
            );
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

