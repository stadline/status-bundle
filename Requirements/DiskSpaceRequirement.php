<?php

namespace Stadline\StatusPageBundle\Requirements;

class DiskSpaceRequirement extends AppRequirementCollection implements RequirementCollectionInterface
{
    public function __construct()
    {
        // check disk space
        $cacheDir = sprintf('%s/../app/cache', getcwd());
        $ds = disk_total_space($cacheDir);
        $df = disk_free_space($cacheDir);
        $percentSpace = ($df/$ds)*100;

        if ($percentSpace < 10) {
            $this->addRequirement(false, sprintf("Not enough space available on the disk, remains %s%s", $percentSpace, '%'), "");
        } else {
            $this->addRequirement(true, "Disk space", "");
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Disk space";
    }
}
