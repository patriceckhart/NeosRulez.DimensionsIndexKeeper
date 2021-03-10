<?php
namespace NeosRulez\DimensionsIndexKeeper;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\Core\Bootstrap;
use Neos\ContentRepository\Domain\Model\Node;

class Package extends BasePackage {

    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap) {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(Node::class, 'afterNodeMove', SetIndex::class, 'setIndexOnAllDimensions');
    }

}