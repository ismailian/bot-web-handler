<?php

namespace TeleBot\System\Interfaces;

interface ISessionAdapter
{

    /**
     * default constructor
     *
     * @param string $sessionId
     */
    public function __construct(string $sessionId);

    /**
     * read session
     *
     * @return array
     */
    public function read(): array;

    /**
     * write session data
     *
     * @param array $data
     * @return bool
     */
    public function write(array $data): bool;

}