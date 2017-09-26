<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FilesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FilesTable Test Case
 */
class FilesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FilesTable
     */
    public $Files;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.files',
        'app.works',
        'app.users',
        'app.aros',
        'app.acos',
        'app.permissions',
        'app.groups',
        'app.skills_works',
        'app.skills',
        'app.fields',
        'app.organizations',
        'app.users_organizations',
        'app.organizations_users',
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
        $config = TableRegistry::exists('Files') ? [] : ['className' => FilesTable::class];
        $this->Files = TableRegistry::get('Files', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Files);

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
