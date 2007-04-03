<?php
/**
 * Class to test project's model
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.5
 * @package Db
 * @subpackage Table
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id$
 */

// Call USVN_Auth_Adapter_DbTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "USVN_Auth_Adapter_DbTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'www/USVN/autoload.php';

/**
 * Test class for USVN_Auth_Adapter_Db.
 * Generated by PHPUnit_Util_Skeleton on 2007-03-25 at 09:51:30.
 */
class USVN_Db_Table_ProjectsTest extends USVN_Test_DB {

    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("USVN_Auth_Adapter_DbTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp() {
		parent::setUp();

    }

    public function testInsertProjectNoName()
	{
		$table = new USVN_Db_Table_Projects();
		$obj = $table->fetchNew();
		$obj->setFromArray(array('projects_name' => ''));
		$_id = $obj->save();
		//test sur le nombre d'entree avant et apres
    }

    public function testInsertProjectOk()
	{
		$table = new USVN_Db_Table_Projects();
		$obj = $table->fetchNew();
		$obj->setFromArray(array('projects_name' => 'test'));
		$obj->save();
		$this->assertTrue($table->isAProject('test'));
    }

    public function testUpdateProjectNoName()
	{
		$table = new USVN_Db_Table_Projects();
		$obj = $table->fetchNew();
		$obj->setFromArray(array('projects_name' => 'test'));
		$id = $obj->save();
		$obj = $table->find($id);
		$obj->setFromArray(array('projects_name' => ''));
		//test sur le nombre d'entree avant et apres
    }

    public function testUpdateProjectOk()
	{
		$table = new USVN_Db_Table_Projects();
		$obj = $table->fetchNew();
		$obj->setFromArray(array('projects_name' => 'test'));
		$id = $obj->save();
		$this->assertTrue($table->isAProject('test'));
		$obj = $table->find($id);
		$obj->setFromArray(array('projects_name' => 'testOK'));
		$id = $obj->save();
		$this->assertTrue($table->isAProject('testOK'));
    }
}

// Call USVN_Auth_Adapter_DbTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "USVN_Auth_Adapter_DbTest::main") {
    USVN_Auth_Adapter_DbTest::main();
}
?>
