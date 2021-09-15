<?php

namespace Importer;

use Exception;

class ImporterError
{
    /**
     * @throws Exception
     */
    public function onNoOptions()
    {
        throw new Exception("\nNo options provided\n");
    }

    /**
     * @throws Exception
     */
    public function onNoFilePath()
    {
        throw new Exception("\nNo file path provided\n");
    }

    /**
     * @throws Exception
     */
    public function invalidOption()
    {
        throw new Exception("\nInvalid option provided\n");
    }

    /**
     * @throws Exception
     */
    public function onInvalidPath()
    {
        throw new Exception("\nInvalid file path provided\n");
    }

}