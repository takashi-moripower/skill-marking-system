<?php
namespace App\Test\TestCase\Controller;

use App\Controller\OrganizationsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\OrganizationsController Test Case
 */
class OrganizationsControllerTest extends IntegrationTestCase
{

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
        'app.users_organizations',
        'app.files',
        'app.junles',
        'app.junles_works'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
