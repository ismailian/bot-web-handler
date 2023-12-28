<?php

namespace TeleBot\System\Types;

use TeleBot\System\Interfaces\IFile;

class File implements IFile
{

    private string $resourceUrl = 'https://api.telegram.org/file/bot{token}/{path}?file_id={file_id}';

    /**
     * @inheritDoc
     */
    public function getLink(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function download(string $path, string $filename = null): bool
    {
        return true;
    }
}