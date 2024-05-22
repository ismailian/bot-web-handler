<?php

namespace TeleBot\System\Database;

trait Variables
{
    /** @var string $dbHostname The database hostname */
    private string $dbHostname;

    /** @var string $dbUsername The database username */
    private string $dbUsername;

    /** @var string $dbPassword The database password */
    private string $dbPassword;

    /** @var string $dbDatabase The database name */
    private string $dbDatabase;

    /** @var mixed $dbResource The database resource */
    public mixed $dbResource;

    /** @var string $dbQuery The last stored query */
    public string $dbQuery;

    /** @var array $keyContainer Contains keys */
    private array $_keys = [];

    /** @var array $valueContainer Contains values */
    private array $_values = [];

    /** @var string $_command a property holding the command name */
    private string $_command;

    /** @var string $_table a property holding the table name */
    private string $_table;

    /** @var array $_field a collection of fields to fetch when using SELECT command */
    private array $_fields = ['*'];

    /** @var array $_set a collection of data in [set] clause */
    private array $_set = [];

    /** @var array $_where a collection of data in [where] clause */
    private array $_where = [];

    /** @var int $_limit a property indicating the limit value */
    private int $_limit = 0;

    /** @var array $_orderBy a collection or data in [order by] clause */
    private array $_orderBy = [];
}
