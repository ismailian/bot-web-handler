<?php

namespace TeleBot\System\Database;

use PDO;
use Exception;
use PDOException;
use AllowDynamicProperties;

#[AllowDynamicProperties]
class  DbClient
{

    use Variables;
    use QueryBuilder;
    use Command;
    use Clause;

    /**
     * default constructor
     *
     * @param string $hostname the database server.
     * @param string $username the database username.
     * @param string $password the database password.
     * @param string $database the database name.
     * @throws Exception
     */
    function __construct(string $hostname, string $username, string $password, string $database)
    {
        $this->dbHostname = $hostname;
        $this->dbUsername = $username;
        $this->dbPassword = $password;
        $this->dbDatabase = $database;

        /** pdo options */
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );

        try {
            $this->dbResource = new \PDO(
                "mysql:host={$this->dbHostname};dbname={$this->dbDatabase}",
                $this->dbUsername,
                $this->dbPassword,
                $options
            );
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get instance of this class
     *
     * @throws Exception
     */
    public static function instance(): DbClient
    {
        return (new DbClient(
            getenv('DATABASE_HOST', true),
            getenv('DATABASE_USER', true),
            getenv('DATABASE_PASS', true),
            getenv('DATABASE_NAME', true),
        ));
    }

}
