<?php  namespace AppBundle\Validator\Constraints;

use Monolog\Logger;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlAvailabilityValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {

        $ch = curl_init($value);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_exec($ch);

        if (curl_errno($ch)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200)
            $this->context->buildViolation($constraint->message)
                ->addViolation();

        curl_close($ch);

    }
}