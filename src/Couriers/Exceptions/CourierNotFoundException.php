<?php

namespace Laracommerce\Core\Couriers\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CourierNotFoundException extends NotFoundHttpException
{
    /**
     * CourierNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Courier not found.');
    }
}
