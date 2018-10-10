<?php

namespace micetm\Clients\ServiceDiscounts\exceptions;

use Exception;

class RequiredFieldsEmpty extends Exception
{
    /**
     * @var array
     */
    private $requiredFields;

    public function __construct(array $requiredFieldsArray, $code = 0, $previous = null)
    {
        $this->requiredFields = $requiredFieldsArray;
        parent::__construct(implode(', ', $requiredFieldsArray) . ' can\'t be empty', $code, $previous);
    }

    /**
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->requiredFields;
    }
}
