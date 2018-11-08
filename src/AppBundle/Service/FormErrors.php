<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Service;

use Symfony\Component\Form\FormInterface;

/**
 * Class FormErrors
 * @package AppBundle\Service
 */
class FormErrors
{
    /**
     * @param FormInterface $form
     * @return array
     */
    public function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->all() as $child) {
            $fieldName = $child->getName();
            $fieldErrors = $form->get($child->getName())->getErrors(true);

            foreach ($fieldErrors as $fieldError) {
                $errors[$fieldName] = $fieldError->getMessage();
            }
        }

        return $errors;
    }
}