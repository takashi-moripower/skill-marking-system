<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConditionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConditionsTable Test Case
 */
class ConditionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ConditionsTable
     */
    public $Conditions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.conditions',
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
        'app.skills_works',
        'app.engineers',
        'app.condition_options',
        'app.conditions_skills'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Conditions') ? [] : ['className' => ConditionsTable::class];
        $this->Conditions = TableRegistry::get('Conditions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Conditions);

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
