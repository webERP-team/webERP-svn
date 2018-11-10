<?php

/**
 * This file is part of the BarcodePack - PHP Barcode Library.
 * Copyright (c) 2011 Tomáš Horáček (http://www.barcodepack.com)
 * BarcodePack by Tomáš Horáček is licensed under
 * a Creative Commons Attribution-NoDerivs 3.0 Unported License.
 */



require_once 'class.linearBarcode.php';

/**
 * s2of5
 *
 * Standard 2/5 (Interleaved 2/5)
 *
 * @author Tomáš Horáček <info@webpack.cz>
 * @package BarcodePack
 */
class s2of5 extends linearBarcode {

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
		'0' => '10101110111010',
		'1' => '11101010101110',
		'2'	=> '11101110101010',
		'3' => '11101110101010',
		'4'	=> '10101110101110',
		'5'	=> '11101011101010',
		'6' => '10111011101010',
		'7' => '10101011101110',
		'8' => '11101010111010',
		'9' => '10111010111010',
		'START' => '1110111010',
		'STOP' => '111010111',
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

		// START char
		$biteCode['START'] = $this->codeTable['START'];

		// Input data encode
		$biteCode['DATA'] = '';
		for($i=0;$i<strlen($this->text);$i++) {
			$biteCode['DATA'] .= $this->codeTable[$this->text{$i}];
		}

		// STOP char
		$biteCode['STOP'] = $this->codeTable['STOP'];

		return $biteCode;
	}

}
