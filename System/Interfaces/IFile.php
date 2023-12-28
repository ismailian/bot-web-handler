<?php

namespace TeleBot\System\Interfaces;

interface IFile
{

    /**
     * get file temporary link
     *
     * @return string
     */
    public function getLink(): string;

    /**
     * download context file
     *
     * @param string $path directory to store the file in
     * @param string|null $filename name of the file, defaults to remote filename
     * @return bool
     */
    public function download(string $path, string $filename = null): bool;

}