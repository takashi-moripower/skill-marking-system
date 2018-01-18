<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EngineersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EngineersTable Test Case
 */
class EngineersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EngineersTable
     */
    public $Engineers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.engineers',
        'app.users',
        'app.aros',
        'app.acos',
        'app.permissions',
        'app.groups',
        'app.works',
        'app.files',
        'app.comments',
        'app.junles',
        'app.junles_works',
        'app.skills',
        'app.fields',
        'app.organizations',
        'app.organizations_users',
        'app.skills_works'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Engineers') ? [] : ['className' => EngineersTable::class];
        $this->Engineers = TableRegistry::get('Engineers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Engineers);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
