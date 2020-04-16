<?php declare(strict_types=1);
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rss\Test\Unit\Model;

use Magento\Framework\App\Rss\DataProviderInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Rss\Model\RssManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RssManagerTest extends TestCase
{
    /**
     * @var RssManager
     */
    protected $rssManager;

    /**
     * @var ObjectManagerInterface|MockObject
     */
    protected $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = $this->createMock(ObjectManagerInterface::class);

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->rssManager = $objectManagerHelper->getObject(
            RssManager::class,
            [
                'objectManager' => $this->objectManager,
                'dataProviders' => [
                    'rss_feed' => DataProviderInterface::class,
                    'bad_rss_feed' => 'Some\Class\Not\Existent',
                ]
            ]
        );
    }

    public function testGetProvider()
    {
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $this->objectManager->expects($this->once())->method('get')->will($this->returnValue($dataProvider));

        $this->assertInstanceOf(
            DataProviderInterface::class,
            $this->rssManager->getProvider('rss_feed')
        );
    }

    public function testGetProviderFirstException()
    {
        $this->expectException('InvalidArgumentException');
        $this->rssManager->getProvider('wrong_rss_feed');
    }

    public function testGetProviderSecondException()
    {
        $this->expectException('InvalidArgumentException');
        $this->rssManager->getProvider('bad_rss_feed');
    }
}
