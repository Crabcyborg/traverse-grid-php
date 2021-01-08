<?php

/**
 * A simple library for traversing grids in a nearly infinite number of combinations.
 */
class TraverseGrid {

	/**
	 * Constructor for TraverseGrid
	 */
	public function __construct() {

	}

	/**
	 * Traverse a grid the standard way, like a typewriter.
	 *
	 * @param int $height the height of our grid.
	 * @param int $width the width of our grid.
	 * @return function
	 */
	public function horizontal( $height, $width ) {
		$initialize = function( $height, $width ) {
			return array( 'e', 0, 0, 0, 0, $width );
		};
		$update     = function( $details ) {
			return array( 'e', 0, ++ $details['base_y'], 0, $details['base_y'], $details['width'] );
		};
		return $this->callback( $initialize, $update )( $height, $width );
	}

	/**
	 * Traverse a grid from top to bottom.
	 *
	 * @param int $height the height of our grid.
	 * @param int $width the width of our grid.
	 * @return function
	 */
	public function vertical( $height, $width ) {
		return $this->swap( $this->horizontal( $width, $height ) );
	}

	/**
	 * Swap all grid values so that x is y and y is x.
	 *
	 * @param array $details the data returned by one of the pattern functions.
	 * @return array
	 */
	public function swap( $details ) {
		$keyed = array();
		foreach ( $details['points'] as $point ) {
			list( $x, $y ) = $point;
			$keyed[ $y . ',' . $x ] = $details['keyed'][ $x . ',' . $y ];
		}
		$height = $details['width'];
		$width  = $details['height'];
		return $this->details( array_merge( $details, compact( 'keyed', 'width', 'height' ) ) );
	}

	/**
	 * Define a pattern sequence.
	 *
	 * @param function  $initialize the function to call on initialize.
	 * @param function  $update the function to call at the each of each iteration.
	 * @param int|false $size the number of items in our sequence.
	 */
	public function callback( $initialize, $update, $size = false ) {
		return function( $height, $width ) use ( $initialize, $update, $size ) {
			$keyed     = array();
			$remaining = $size ? $size : ( $height * $width );
			$index     = 0;
			$iteration = 0;

			$initialize_result                                     = $initialize( $height, $width );
			list ( $direction, $x, $y, $base_x, $base_y, $target ) = $initialize_result;

			while ( $remaining -- ) {
				$previous               = $index ++;
				$keyed[ $x . ',' . $y ] = $previous;
				switch ( $direction ) {
					case 'n':
						-- $y;
						break;
					case 's':
						++ $y;
						break;
					case 'w':
						-- $x;
						break;
					case 'e':
						++ $x;
						break;
					case 'ne':
						-- $y;
						++ $x;
						break;
					case 'nw':
						-- $y;
						-- $x;
						break;
					case 'se':
						++ $y;
						++ $x;
						break;
					case 'sw':
						++ $y;
						-- $x;
						break;
				}
				if ( ++ $iteration === $target ) {
					$update_body                                          = compact( 'direction', 'x', 'y', 'height', 'width', 'base_x', 'base_y' );
					$update_body['index']                                 = $previous;
					$update_result                                        = $update( $update_body );
					list( $direction, $x, $y, $base_x, $base_y, $target ) = $update_result;
					$iteration                                            = 0;
				}
			}
			return $this->details( compact( 'keyed', 'height', 'width' ) );
		};
	}

	/**
	 * Fill in the missing details after running a pattern function to make a pattern easier to use.
	 *
	 * @param array $details the incomplete data returned by one of the pattern functions.
	 */
	private function details( $details ) {
		$height  = $details['height'];
		$width   = $details['width'];
		$keyed   = $details['keyed'];
		$size    = $width * $height;
		$points  = array();
		$indices = array();

		for ( $y = 0; $y < $height; ++ $y ) {
			for ( $x = 0; $x < $width; ++ $x ) {
				$points[ $keyed[ $x . ',' . $y ] ] = array( $x, $y );
				$indices[]                         = $keyed[ $x . ',' . $y ];
			}
		}

		return array_merge( $details, compact( 'points', 'indices' ) );
	}

	/**
	 * Get a string representation for a pattern.
	 *
	 * @param array $details the data returned by one of the pattern functions.
	 * @return string
	 */
	public function visualize( $details ) {
		$output = array();
		$row    = array();
		for ( $y = 0; $y < $details['height']; ++ $y ) {
			for ( $x = 0; $x < $details['width']; ++ $x ) {
				$row[] = str_pad( $details['keyed'][ $x . ',' . $y ], 2, '0', STR_PAD_LEFT );
			}
			$output[] = implode( ' ', $row );
			$row      = array();
		}
		return implode( "\n", $output );
	}
}
