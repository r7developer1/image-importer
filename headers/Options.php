<?php

namespace Importer;

use Exception;

class Options {
    const DATA = '-d';
    const HELP = '-h';
    private ?string $options;

    /**
     * @throws Exception
     */
    public function __construct(string $options = null , string $filepath = null)
    {
        $this->options = $options;
    }

    /**
     * @throws Exception
     */
    public function __invoke($options , $filepath)
    {
        if (!$this->onNoOptions($options , $filepath)){
            if ($this->onData()){
                return new Importer($filepath);
            }else{
                (new ImporterError())->invalidOption();
            }
        }
        return new Exception("something unexpected happened");
    }


    private function onData(): bool
    {
        if ($this->options == self::DATA){
            return true;
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function onNoOptions(?string $option , ?string $filepath)
    {
        if (empty($option)){
            return (new ImporterError())->onNoOptions();
        }

        if (empty($filepath)){
            return (new ImporterError())->onNoFilePath();
        }

        return false;
    }
}