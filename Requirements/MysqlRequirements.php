<?php

namespace Stadline\StatusPageBundle\Requirements;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;

class MysqlRequirements extends \RequirementCollection implements RequirementCollectionInterface
{
    /**
     * MysqlRequirement constructor.
     *
     * @param string $host
     * @param string $name
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $name, $user, $password, ConnectionFactory $factory)
    {
        try {
            $factory->createConnection(
                array('pdo' => new \PDO("mysql:host=$host;dbname=$name", $user, $password))
            );
            $this->addRequirement(true, "Mysql connection", "");

        } catch (\PDOException $e) {
            $this->addRequirement(false, "Mysql connection requirement failed", $e->getMessage());
        }
    }

    public function getName()
    {
        return "mysql";
    }
}
