<?php

namespace micetm\Clients\ServiceDiscounts\exceptions;

use Exception;

class DiscountNotFound extends Exception
{
    /**
     * @var string
     */
    private $codeId;

    public function __construct($codeId, $code = 0, $previous = null)
    {
        parent::__construct('Discount ' . $codeId . ' not found', $code, $previous);
        $this->codeId = $codeId;
    }

    /**
     * @return string
     */
    public function getCodeId()
    {
        return $this->codeId;
    }
}
