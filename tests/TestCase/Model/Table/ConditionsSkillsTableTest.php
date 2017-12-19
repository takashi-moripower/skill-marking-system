<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConditionsSkillsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConditionsSkillsTable Test Case
 */
class ConditionsSkillsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ConditionsSkillsTable
     */
    public $ConditionsSkills;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.conditions_skills',
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
        'app.condition_options'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ConditionsSkills') ? [] : ['className' => ConditionsSkillsTable::class];
        $this->ConditionsSkills = TableRegistry::get('ConditionsSkills', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ConditionsSkills);

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
