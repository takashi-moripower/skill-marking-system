<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SkillsWorksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SkillsWorksTable Test Case
 */
class SkillsWorksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SkillsWorksTable
     */
    public $SkillsWorks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.skills_works',
        'app.skills',
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
        'app.organizations_users',
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
        $config = TableRegistry::exists('SkillsWorks') ? [] : ['className' => SkillsWorksTable::class];
        $this->SkillsWorks = TableRegistry::get('SkillsWorks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SkillsWorks);

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
