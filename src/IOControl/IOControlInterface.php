<?php
namespace Hooloovoo\Framework\IOControl;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface IOControlInterface
 */
interface IOControlInterface
{
    /**
     * @param Request $request
     */
    public function prepareRequest(Request $request) ;
}