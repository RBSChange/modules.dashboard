<?php
abstract class dashboard_tests_AbstractBaseUnitTest extends dashboard_tests_AbstractBaseTest
{
	/**
	 * @return void
	 */
	public function prepareTestCase()
	{
		$this->resetDatabase();
	}
}