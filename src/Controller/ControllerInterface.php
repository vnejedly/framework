<?php
namespace Hooloovoo\Framework\Controller;

use Hooloovoo\Framework\IOControl\IOControlInterface;

/**
 * Interface ControllerInterface
 */
interface ControllerInterface
{
    /**
     * @return IOControlInterface
     */
    public function getIOControl() : IOControlInterface ;
}