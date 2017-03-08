<?php namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UrlAvailability extends Constraint
{
    public $message = 'Your url is not availability now!';
}