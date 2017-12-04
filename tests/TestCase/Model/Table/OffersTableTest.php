<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OffersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OffersTable Test Case
 */
class OffersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OffersTable
     */
    public $Offers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.offers',
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
        'app.companies',
        'app.offers_skills'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Offers') ? [] : ['className' => OffersTable::class];
        $this->Offers = TableRegistry::get('Offers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Offers);

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
