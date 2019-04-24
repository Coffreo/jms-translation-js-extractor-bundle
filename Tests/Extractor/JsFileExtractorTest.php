<?php

/**
 * This file is part of Coffreo project "coffreo/jms-translation-js-extractor-bundle"
 *
 * (c) Coffreo SAS <contact@coffreo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Coffreo\JMSTranslationJsExtractorBundle\Tests\Extractor;

use Coffreo\JMSTranslationJsExtractorBundle\Extractor\JsFileExtractor;
use Coffreo\JsTranslationExtractor\Extractor\JsTranslationExtractor;
use Coffreo\JsTranslationExtractor\Model\TranslationCollection;
use Coffreo\JsTranslationExtractor\Model\TranslationString;
use JMS\TranslationBundle\Model\FileSource;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Model\MessageCatalogue;
use JMS\TranslationBundle\Translation\FileSourceFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class JsFileExtractorTest.
 *
 * @covers \Coffreo\JMSTranslationJsExtractorBundle\Extractor\JsFileExtractor
 *
 * @author  Cyril MERY <cmery@coffreo.com>
 */
class JsFileExtractorTest extends TestCase
{
    public function getValidSources()
    {
        return [
            ['Resources/vanilla.js'],
            ['Resources/react.jsx'],
            ['Resources/custom.extension'],
        ];
    }

    /**
     * @dataProvider getValidSources
     *
     * @param $file
     */
    public function testValidExtraction($file)
    {
        $collectionStub = new TranslationCollection();
        $collectionStub->addTranslation(
            new TranslationString('foo', 10, ['domain' => 'messages'])
        );

        $baseExtractorMock = $this
            ->getMockBuilder(JsTranslationExtractor::class)
            ->setMethods(['extract'])
            ->getMock();

        $baseExtractorMock->expects($this->once())
            ->method('extract')
            ->with('')
            ->willReturn($collectionStub);

        $extractor = new JsFileExtractor($this->getFileSourceFactory(), ['js', 'jsx', 'extension'], $baseExtractorMock);

        $absFilepath = \realpath(__DIR__.'/../'.$file);

        $catalogue = new MessageCatalogue();
        $extractor->visitFile(new \SplFileInfo($absFilepath), $catalogue);

        $expectedMessage = new Message('foo', 'messages');
        $expectedMessage->setSources([new FileSource($file, 10)]);

        $this->assertCount(1, $catalogue->getDomains());
        $this->assertEquals($expectedMessage, $catalogue->get('foo', 'messages'));
    }

    public function testDoNotExtractUnwantedExtension()
    {
        $extractor = new JsFileExtractor($this->getFileSourceFactory(), ['js', 'jsx']);
        $file = \realpath(__DIR__.'/../Resources/unknown');
        $catalogue = new MessageCatalogue();
        $extractor->visitFile(new \SplFileInfo($file), $catalogue);
        $this->assertCount(0, $catalogue->getDomains());
    }

    /**
     * @group legacy
     */
    public function testVisitPhpAndTwigMustNotTriggerExtraction()
    {
        $baseExtractorMock = $this
            ->getMockBuilder(JsTranslationExtractor::class)
            ->setMethods(['extract'])
            ->getMock();

        $baseExtractorMock->expects($this->never())->method('extract');
        $catalogue = new MessageCatalogue();
        $extractor = new JsFileExtractor($this->getFileSourceFactory(), ['js', 'jsx'], $baseExtractorMock);
        $file = new \SplFileInfo(\realpath(__DIR__.'/../Resources/unknown'));
        $extractor->visitPhpFile($file, $catalogue, []);
        $extractor->visitTwigFile($file, $catalogue, new \Twig_Node());
    }

    protected function getFileSourceFactory()
    {
        return new FileSourceFactory(dirname(__DIR__).'/');
    }
}
