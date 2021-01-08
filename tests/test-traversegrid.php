<?php

/**
 * Unit Test class for MinString.php
 */
class UnitTest_TraverseGrid extends \PHPUnit\Framework\TestCase {

	/**
	 * Test TraverseGrid::horizontal
	 */
	public function test_horizontal() {
		$traverse   = new TraverseGrid();
		$horizontal = $traverse->horizontal( 3, 3 );
		$expected   = $this->get_expected_horizontal_3x3_result();
		$this->assertEquals( $expected, $traverse->visualize( $horizontal ) );
	}

	/**
	 * Get the expected result for a horizontal pattern in a 3x3 grid
	 */
	private function get_expected_horizontal_3x3_result() {
		return <<<EOT
00 01 02
03 04 05
06 07 08
EOT;
	}

	/**
	 * Test TraverseGrid::vertical
	 */
	public function test_vertical() {
		$traverse = new TraverseGrid();
		$vertical = $traverse->vertical( 3, 3 );
		$expected = $this->get_expected_vertical_3x3_result();
		$this->assertEquals( $expected, $traverse->visualize( $vertical ) );
	}

	/**
	 * Get the expected result for a vertical pattern in a 3x3 grid
	 */
	private function get_expected_vertical_3x3_result() {
		return <<<EOT
00 03 06
01 04 07
02 05 08
EOT;
	}

}
