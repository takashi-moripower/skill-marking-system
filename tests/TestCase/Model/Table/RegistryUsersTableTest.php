<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RegistryUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RegistryUsersTable Test Case
 */
class RegistryUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RegistryUsersTable
     */
    public $RegistryUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.registry_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RegistryUsers') ? [] : ['className' => RegistryUsersTable::class];
        $this->RegistryUsers = TableRegistry::get('RegistryUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RegistryUsers);

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
