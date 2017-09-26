<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FieldsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FieldsTable Test Case
 */
class FieldsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FieldsTable
     */
    public $Fields;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.fields',
        'app.organizations',
        'app.users',
        'app.aros',
        'app.acos',
        'app.permissions',
        'app.groups',
        'app.works',
        'app.files',
        'app.junles',
        'app.junles_works',
        'app.skills',
        'app.skills_works',
        'app.organizations_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Fields') ? [] : ['className' => FieldsTable::class];
        $this->Fields = TableRegistry::get('Fields', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Fields);

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

    /**
     * Test createFromArray method
     *
     * @return void
     */
    public function testCreateFromArray()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findUsable method
     *
     * @return void
     */
    public function testFindUsable()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
