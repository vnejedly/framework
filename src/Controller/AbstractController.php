<?php
namespace Hooloovoo\Framework\Controller;

use Exception;
use Hooloovoo\Framework\IOControl\IOControlInterface;

/**
 * Class AbstractController
 */
abstract class AbstractController implements ControllerInterface
{
    /** @var IOControlInterface */
    protected $ioControl;

    /**
     * @param IOControlInterface $ioControl
     */
    public function setIOControl(IOControlInterface $ioControl)
    {
        $this->ioControl = $ioControl;
    }

    /**
     * @return IOControlInterface
     * @throws Exception
     */
    public function getIOControl() : IOControlInterface
    {
        if (
            !$this->ioControl instanceof IOControlInterface
        ) {
            $controllerName = get_called_class();
            $ioControlInterfaceName = IOControlInterface::class;
            throw new Exception("Controller {$controllerName} must have injected {$ioControlInterfaceName} dependency");
        }

        return $this->ioControl;
    }
}