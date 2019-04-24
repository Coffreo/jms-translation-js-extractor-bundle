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

use Coffreo\JMSTranslationJsExtractorBundle\DependencyInjection\CoffreoJMSTranslationJsExtractorExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/** @covers \Coffreo\JMSTranslationJsExtractorBundle\DependencyInjection\CoffreoJMSTranslationJsExtractorExtension */
class CoffreoJMSTranslationJsExtractorExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @param $type
     */
    public function testJMSTranslationServiceIsDefinedWithTagAndType()
    {
        $this->load();

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'coffreo.jms_translation.js_extractor.js',
            'jms_translation.file_visitor'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'coffreo.jms_translation.js_extractor.js',
            1,
            ['js', 'jsx']
        );
    }

    /**
     * @return array|\Symfony\Component\DependencyInjection\Extension\ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return [
            new CoffreoJMSTranslationJsExtractorExtension(),
        ];
    }
}
