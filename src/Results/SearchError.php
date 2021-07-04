<?php

namespace Permafrost\PhpCodeSearch\Results;

class SearchError
{
    /** @var \Exception */
    public $error;

    /** @var string */
    public $message;

    public function __construct(\Exception $error, string $message)
    {
        $this->error = $error;
        $this->message = $message;
    }
}
