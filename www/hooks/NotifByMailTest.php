<?php
/**
 * Send an email after each commit
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.8
 * @package usvn
 * @subpackage hooks
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id$
 */

// Call NotifByMailTest:main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "NotifByMailTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'www/USVN/autoload.php';
require_once 'www/hooks/NotifByMail.php';

class FakeSendMail extends Zend_Mail_Transport_Sendmail
{
	public function _sendMail()
	{
	}

	public function getSubject()
	{
		return $this->_mail->getSubject();
	}

	public function getBodyText()
	{
		return $this->_mail->getBodyText(true);
	}

	public function getFrom()
	{
		return $this->_mail->getFrom();
	}

	public function getTo()
	{
		return $this->_mail->getRecipients();
	}
}


/**
 * Test class for NotifByMail.
 * Generated by PHPUnit_Util_Skeleton on 2007-03-26 at 17:42:45.
 */
class NotifByMailTest extends USVN_Test_DB {
	private $project = "testrepos";
	private $repos = "tests/tmp/svn/testrepos";
	private $co = "tests/checkout";
	private $sendmail;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("NotifByMailTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

	public function setUp()
	{
		parent::setUp();
		$table = new USVN_Db_Table_Users();
		$user = $table->fetchNew();
		$user->setFromArray(array('users_login' 	=> 'test',
																'users_password' 	=> 'password',
																'users_firstname' 	=> 'firstname',
																'users_lastname' 	=> 'lastname',
																'users_email' 		=> 'email@email.fr'));
		$user->save();


		$this->sendmail = new FakeSendMail();
		//USVN_ConsoleUtils::runCmd(USVN_SVNUtils::svnadminCommand("create " . escapeshellarg($this->repos)));
		USVN_Project::createProject(array('projects_name'  => $this->project), "test", true, false, false);
		USVN_DirectoryUtils::removeDirectory($this->repos . DIRECTORY_SEPARATOR . 'hooks');
		USVN_SVNUtils::checkoutSvn($this->repos, $this->co);
		$path = getcwd();
		chdir($this->co);
		mkdir('testdir');
		`svn add testdir`;
		`svn commit --non-interactive -m Test`;
		chdir($path);

		Zend_Mail::setDefaultTransport($this->sendmail);
	}

	public function testpostCommit()
	{
		$h = new HookNotifByMail();
		$h->postCommit($this->repos, 1);
		$this->assertEquals('Commit 1', $this->sendmail->getSubject());
		$this->assertEquals('Commit 1', $this->sendmail->getBodyText());
		$this->assertEquals('nobody@usvn.info', $this->sendmail->getFrom());
		$this->assertEquals(array('noplay@localhost'), $this->sendmail->getTo());
	}
}

// Call USVN_ConfigTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "NotifByMailTest::main") {
    NotifByMailTest::main();
}