<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrganizationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrganizationsTable Test Case
 */
class OrganizationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OrganizationsTable
     */
    public $Organizations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.organizations',
        'app.fields',
        'app.skills',
        'app.works',
        'app.users',
        'app.aros',
        'app.acos',
        'app.permissions',
        'app.groups',
        'app.skills_works',
        'app.organizations_users',
        'app.files',
        'app.junles',
        'app.junles_works',
        'app.users_organizations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Organizations') ? [] : ['className' => OrganizationsTable::class];
        $this->Organizations = TableRegistry::get('Organizations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Organizations);

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
