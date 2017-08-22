<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JunlesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JunlesTable Test Case
 */
class JunlesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JunlesTable
     */
    public $Junles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.junles',
        'app.works',
        'app.junles_works'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Junles') ? [] : ['className' => JunlesTable::class];
        $this->Junles = TableRegistry::get('Junles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Junles);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
