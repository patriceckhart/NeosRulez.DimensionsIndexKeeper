<?php
namespace NeosRulez\DimensionsIndexKeeper;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Exception\NodeException;

class SetIndex {

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @Flow\InjectConfiguration(package="Neos.ContentRepository")
     * @var array
     */
    protected $contentRepositorySettings;


    /**
     * @throws NodeException
     * @param NodeInterface $movedNode
     * @param NodeInterface $referenceNode
     * @param integer $movePosition
     * @return void
     */
    public function setIndexOnAllDimensions(NodeInterface $movedNode, NodeInterface $referenceNode, int $movePosition) {
        if(array_key_exists('contentDimensions', $this->contentRepositorySettings) && array_key_exists('language', $this->contentRepositorySettings['contentDimensions']) && array_key_exists('presets', $this->contentRepositorySettings['contentDimensions']['language']) && array_key_exists('defaultPreset', $this->contentRepositorySettings['contentDimensions']['language'])) {
            $languagePresets = $this->contentRepositorySettings['contentDimensions']['language']['presets'];
            $defaultPreset = $this->contentRepositorySettings['contentDimensions']['language']['defaultPreset'];
            foreach ($languagePresets as $i => $languagePreset) {
                $context = $this->contextFactory->create(
                    [
                        'workspaceName' => 'live',
                        'currentDateTime' => new \Neos\Flow\Utility\Now(),
                        'dimensions' => ['language' => [$i, $defaultPreset]],
                        'targetDimensions' => ['language' => $i],
                        'invisibleContentShown' => false,
                        'removedContentShown' => false,
                        'inaccessibleContentShown' => false
                    ]
                );
                $node = $context->getNodeByIdentifier($movedNode->getIdentifier());
                if($node) {
                    $node->setIndex($movedNode->getIndex());
                }
            }
        }
    }

}