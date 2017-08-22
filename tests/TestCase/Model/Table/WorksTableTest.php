<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WorksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WorksTable Test Case
 */
class WorksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WorksTable
     */
    public $Works;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.works',
        'app.users',
        'app.aros',
        'app.acos',
        'app.permissions',
        'app.groups',
        'app.skills_works',
        'app.organizations',
        'app.fields',
        'app.skills',
        'app.users_organizations',
        'app.organizations_users',
        'app.files',
        'app.junles',
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
        $config = TableRegistry::exists('Works') ? [] : ['className' => WorksTable::class];
        $this->Works = TableRegistry::get('Works', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Works);

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
