<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *  Test APIv3 civicrm_website_* functions
 *
 * @package CiviCRM_APIv3
 * @subpackage API_Contact
 * @group headless
 */
class api_v3_WebsiteTest extends CiviUnitTestCase {
  protected $_apiversion = 3;
  protected $params;
  protected $id;
  protected $_entity;

  public function setUp(): void {
    parent::setUp();
    $this->useTransaction();

    $this->_entity = 'website';
    $contactID = $this->organizationCreate();
    $this->params = [
      'contact_id' => $contactID,
      'url' => 'website.com',
      'website_type_id' => 1,
    ];
  }

  /**
   * @param int $version
   * @dataProvider versionThreeAndFour
   */
  public function testCreateWebsite($version) {
    $this->_apiversion = $version;
    $result = $this->callAPIAndDocument($this->_entity, 'create', $this->params, __FUNCTION__, __FILE__);
    $this->assertEquals(1, $result['count']);
    $this->getAndCheck($this->params, $result['id'], $this->_entity);
    $this->assertNotNull($result['values'][$result['id']]['id']);
  }

  /**
   * @param int $version
   * @dataProvider versionThreeAndFour
   */
  public function testGetWebsite($version) {
    $this->_apiversion = $version;
    $result = $this->callAPISuccess($this->_entity, 'create', $this->params);
    $result = $this->callAPIAndDocument($this->_entity, 'get', $this->params, __FUNCTION__, __FILE__);
    $this->assertEquals(1, $result['count']);
    $this->assertNotNull($result['values'][$result['id']]['id']);
    $this->callAPISuccess('website', 'delete', ['id' => $result['id']]);
  }

  /**
   * @param int $version
   * @dataProvider versionThreeAndFour
   */
  public function testDeleteWebsite($version) {
    $this->_apiversion = $version;
    $result = $this->callAPISuccess($this->_entity, 'create', $this->params);

    $beforeCount = CRM_Core_DAO::singleValueQuery('SELECT count(*) FROM civicrm_website');
    $this->assertGreaterThanOrEqual(1, $beforeCount);

    $deleteParams = ['id' => $result['id']];
    $result = $this->callAPIAndDocument($this->_entity, 'delete', $deleteParams, __FUNCTION__, __FILE__);

    $afterCount = CRM_Core_DAO::singleValueQuery('SELECT count(*) FROM civicrm_website');
    $this->assertEquals($beforeCount - 1, $afterCount);
  }

  /**
   * @param int $version
   * @dataProvider versionThreeAndFour
   */
  public function testDeleteWebsiteInvalid($version) {
    $this->_apiversion = $version;
    $result = $this->callAPISuccess($this->_entity, 'create', $this->params);

    $beforeCount = CRM_Core_DAO::singleValueQuery('SELECT count(*) FROM civicrm_website');
    $this->assertGreaterThanOrEqual(1, $beforeCount);

    $deleteParams = ['id' => 600];
    $result = $this->callAPIFailure($this->_entity, 'delete', $deleteParams);

    $afterCount = CRM_Core_DAO::singleValueQuery('SELECT count(*) FROM civicrm_website');
    $this->assertEquals($beforeCount, $afterCount);
  }

  /**
   * Test retrieval of metadata.
   */
  public function testGetMetadata() {
    $result = $this->callAPIAndDocument($this->_entity, 'get', [
      'options' => [
        'metadata' => ['fields'],
      ],
    ], __FUNCTION__, __FILE__, 'Demonostrates returning field metadata', 'GetWithMetadata');
    $this->assertEquals('Website', $result['metadata']['fields']['url']['title']);
  }

  /**
   * @param int $version
   * @dataProvider versionThreeAndFour
   */
  public function testGetFields($version) {
    $this->_apiversion = $version;
    $result = $this->callAPIAndDocument($this->_entity, 'getfields', ['action' => 'get'], __FUNCTION__, __FILE__);
    $this->assertArrayKeyExists('url', $result['values']);
  }

}
