<?php

/**
 * This file is part of Coffreo project "coffreo/jms-translation-js-extractor-bundle"
 *
 * (c) Coffreo SAS <contact@coffreo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Coffreo\JMSTranslationJsExtractorBundle\Extractor;

use Coffreo\JsTranslationExtractor\Extractor\JsTranslationExtractor;
use Coffreo\JsTranslationExtractor\Extractor\JsTranslationExtractorInterface;
use Coffreo\JsTranslationExtractor\Model\TranslationCollection;
use Coffreo\JsTranslationExtractor\Model\TranslationString;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Model\MessageCatalogue;
use JMS\TranslationBundle\Translation\Extractor\FileVisitorInterface;
use JMS\TranslationBundle\Translation\FileSourceFactory;

final class JsFileExtractor implements FileVisitorInterface
{
    /**
     * @var FileSourceFactory
     */
    private $fileSourceFactory;

    /**
     * @var JsTranslationExtractorInterface
     */
    private $extractor;

    /**
     * @var string[]
     */
    private $extensions;

    /**
     * JsFileExtractor constructor.
     *
     * @param FileSourceFactory                    $fileSourceFactory
     * @param string[]                             $extensions
     * @param JsTranslationExtractorInterface|null $extractor
     */
    public function __construct(FileSourceFactory $fileSourceFactory, $extensions = null, JsTranslationExtractorInterface $extractor = null)
    {
        $this->fileSourceFactory = $fileSourceFactory;
        $this->extensions = $extensions ?: ['js', 'jsx'];
        $this->extractor = $extractor ?: new JsTranslationExtractor();
    }

    /**
     * Called for non-specially handled files.
     *
     * This is not called if handled by a more specific method.
     *
     * @param \SplFileInfo     $file
     * @param MessageCatalogue $catalogue
     */
    public function visitFile(\SplFileInfo $file, MessageCatalogue $catalogue)
    {
        if (!\in_array($file->getExtension(), $this->extensions, true)) {
            return;
        }

        $translations = $this->findTranslations($file);

        foreach ($translations as $translation) {
            /** @var $translation TranslationString */
            $message = new Message($translation->getMessage(), $translation->getContext('domain') ?: 'messages');
            $message->setDesc($translation->getContext('desc'));
            $message->setDesc($translation->getContext('meaning'));
            $message->addSource($this->fileSourceFactory->create($file, $translation->getLine()));
            $catalogue->add($message);
        }
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return TranslationCollection
     */
    public function findTranslations(\SplFileInfo $file)
    {
        return $this->extractor->extract(file_get_contents($file), new TranslationCollection());
    }

    /**
     * Called when a PHP file is encountered.
     *
     * The visitor already gets a parsed AST passed along.
     *
     * @param \SplFileInfo     $file
     * @param MessageCatalogue $catalogue
     * @param array            $ast
     */
    public function visitPhpFile(\SplFileInfo $file, MessageCatalogue $catalogue, array $ast)
    {
    }

    /**
     * Called when a Twig file is encountered.
     *
     * The visitor already gets a parsed AST passed along.
     *
     * @param \SplFileInfo     $file
     * @param MessageCatalogue $catalogue
     * @param \Twig_Node       $ast
     */
    public function visitTwigFile(\SplFileInfo $file, MessageCatalogue $catalogue, \Twig_Node $ast)
    {
    }
}
