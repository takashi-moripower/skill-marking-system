<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RegisteringUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RegisteringUsersTable Test Case
 */
class RegisteringUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RegisteringUsersTable
     */
    public $RegisteringUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.registering_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RegisteringUsers') ? [] : ['className' => RegisteringUsersTable::class];
        $this->RegisteringUsers = TableRegistry::get('RegisteringUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RegisteringUsers);

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
