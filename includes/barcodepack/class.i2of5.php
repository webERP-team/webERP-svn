<?php

/**
 * This file is part of the BarcodePack - PHP Barcode Library.
 * Copyright (c) 2011 Tomáš Horáček (http://www.barcodepack.com)
 * BarcodePack by Tomáš Horáček is licensed under
 * a Creative Commons Attribution-NoDerivs 3.0 Unported License.
 */



require_once 'class.linearBarcode.php';


// Error codes
define('E_ODD_LENGTH', 500);


/**
 * i2of5
 *
 * Interleaved 2/5
 *
 * @author Tomáš Horáček <info@webpack.cz>
 * @package BarcodePack
 */
class i2of5 extends linearBarcode {

	/** @var array */
	private $allowedChars = array(
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
	);

	/**
	 * Coding table
	 *
	 * @var array
	 */
	private $codeTable = array(
		'0' => '1010111011101',
		'1' => '1110101010111',
		'2'	=> '1011101010111',
		'3' => '1110111010101',
		'4'	=> '1010111010111',
		'5'	=> '1110101110101',
		'6' => '1011101110101',
		'7' => '1010101110111',
		'8' => '1110101011101',
		'9' => '1011101011101',
		'START' => '1010',
		'STOP' => '11101',
	);


	/**
	 * Constructor
	 *
	 * @param string $text
	 * @param int $modulesize
	 */
	public function  __construct($text, $moduleSize)
	{
		try {
			parent::__construct($text, $moduleSize, $this->allowedChars);

			if((strlen($this->text)%2)!=0) {
				throw new Exception('The number of characters must be even', E_ODD_LENGTH);
			}

			$this->biteCode = $this->createBiteCode();
		}
		catch(Exception $e) {
			throw $e;
		}

	}


	/**
	 * Create Bite Code
	 *
	 * @return string
	 */
	private function createBiteCode()
	{
		$biteCode = array();

		// START character
		$biteCode['START'] = $this->codeTable['START'];

		$biteCode['DATA'] = '';


		for($i=0;$i<strlen($this->text);$i++) {
			$firstCounter = 0;	// Num of line module
			$secondCounter = 0;	// Num of space module

			// Each char is encoded to 5 lines or spaces
			for($j=0; $j<5; $j++) {

				// Encode first char into lines
				$bars = true;
				while($bars && $firstCounter<13) {
					if($this->codeTable[$this->text{$i}]{$firstCounter}=="1") {
						$biteCode['DATA'] .= '1';	// line
					} else {
						$bars = false;
					}
					$firstCounter++;	// jump to next line
				}

				// Second char is encoded to spaces
				$spaces = true;
				while($spaces && $secondCounter<13) {
					if($this->codeTable[$this->text{$i+1}]{$secondCounter}=='1') {
						$biteCode['DATA'] .= '0';	// space
						$secondCounter++;
					} else {
						$spaces = false;
						$secondCounter++;	// jump to next space
					}
				}
			}

			$i++;	// jump to next char
		}

		// Insert STOP character
		$biteCode['STOP'] = $this->codeTable['STOP'];

		return $biteCode;
	}


}
