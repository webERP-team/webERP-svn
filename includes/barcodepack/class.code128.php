<?php

/**
 * This file is part of the BarcodePack - PHP Barcode Library.
 * Copyright (c) 2011 Tomáš Horáček (http://www.barcodepack.com)
 * BarcodePack by Tomáš Horáček is licensed under
 * a Creative Commons Attribution-NoDerivs 3.0 Unported License.
 */



require_once 'class.linearBarcode.php';

/**
 * Code 128
 * Class implements Code 128 barcode
 *
 * @author Tomáš Horáček <info@webpack.cz>
 * @package BarcodePack
 */
class code128 extends linearBarcode {

	// Code sets
	const CHARSET_A = 'A';
	const CHARSET_B = 'B';
	const CHARSET_C = 'C';

	const START_A = 103;
	const START_B = 104;
	const START_C = 105;
	const STOP = 106;
	const TERMINATION = 107;

	// Code sets switchers
	const CODE_A = 101;
	const CODE_B = 100;
	const CODE_C = 99;


	private $setA = array();
	private $setB = array();
	private $setC = array();

	private $charsA = '';
	private $charsB = '';
	private $charsC = '';

	/**
	 * Zero represents white line
	 * One represents black line
	 *
	 * After each character except STOP I will add one white line
	 * (separating line)
	 *
	 * ASCII (dec) => bitecode
	 *
	 * @var array
	 */
	private $codeTable = array(
		'11011001100',	// 01
		'11001101100',	// 02
		'11001100110',	// 03
		'10010011000',	// 04
		'10010001100',	// ...
		'10001001100',
		'10011001000',
		'10011000100',
		'10001100100',
		'11001001000',
		'11001000100',
		'11000100100',
		'10110011100',
		'10011011100',
		'10011001110',
		'10111001100',
		'10011101100',
		'10011100110',
		'11001110010',
		'11001011100',
		'11001001110',
		'11011100100',
		'11001110100',
		'11101101110',
		'11101001100',
		'11100101100',
		'11100100110',
		'11101100100',
		'11100110100',
		'11100110010',
		'11011011000',
		'11011000110',
		'11000110110',
		'10100011000',
		'10001011000',
		'10001000110',
		'10110001000',
		'10001101000',
		'10001100010',
		'11010001000',
		'11000101000',
		'11000100010',
		'10110111000',
		'10110001110',
		'10001101110',
		'10111011000',
		'10111000110',
		'10001110110',
		'11101110110',
		'11010001110',
		'11000101110',
		'11011101000',
		'11011100010',
		'11011101110',
		'11101011000',
		'11101000110',
		'11100010110',
		'11101101000',
		'11101100010',
		'11100011010',
		'11101111010',
		'11001000010',
		'11110001010',
		'10100110000',
		'10100001100',
		'10010110000',
		'10010000110',
		'10000101100',
		'10000100110',
		'10110010000',
		'10110000100',
		'10011010000',
		'10011000010',
		'10000110100',
		'10000110010',
		'11000010010',
		'11001010000',
		'11110111010',
		'11000010100',
		'10001111010',
		'10100111100',
		'10010111100',
		'10010011110',
		'10111100100',
		'10011110100',
		'10011110010',
		'11110100100',
		'11110010100',
		'11110010010',
		'11011011110',
		'11011110110',
		'11110110110',
		'10101111000',
		'10100011110',
		'10001011110',
		'10111101000',
		'10111100010',
		'11110101000',
		'11110100010',
		'10111011110',
		'10111101110',
		'11101011110',
		'11110101110',
		'11010000100',	// 103 START A
		'11010010000',	// 104 START B
		'11010011100',	// 105 START C
		'11000111010',	// 106 STOP
		'11'			// 107 Termination bar
	);


	/**
	 * Constructor
	 *
	 * @param string $text
	 * @param int $moduleSize
	 */
	public function  __construct($text, $moduleSize=2)
	{
		try {
			// Fill set A
			for($i=32; $i<=95; $i++) {
				// chars SPACE - UNDERSPACE
				$this->setA[$i] = $i - 32;
				$this->charsA .= chr($i);
				$allowedChars[] = chr($i);
			}
			for($i=0; $i<=31; $i++) {
				// chars NUL - US (Unit seperator)
				$this->setA[$i] = $i + 64;
				$this->charsA .= chr($i);
				$allowedChars[] = chr($i);
			}

			/* Fill set B
			 * chars SPACE " " - "DEL"
			 */
			for($i=32; $i<=127; $i++) {
				$this->setB[$i] = $i - 32;
				$this->charsB .= chr($i);
				$allowedChars[] = chr($i);
			}


			parent::__construct($text, $moduleSize, $allowedChars);


			$this->biteCode = $this->createBiteCode();

		} catch (Exception $e) {
			throw $e;
		}

	}


	/**
	 * Create Bite Code
	 *
	 *
	 * @return string
	 */
	private function createBiteCode()
	{
		$biteCode = array();

		$characterSet = self::CHARSET_B;	// Default code set

		$weightedSum = 0;
		$checksumCounter = 1;

		$biteCode['DATA'] = '';
		// Find start character
		if(strlen($this->text)>=2 && is_numeric($this->text{0}) && is_numeric($this->text{1})) {
			// If the first and second characters are numeric use character set C
			// and insert START_C char
			$biteCode['DATA'] .= $this->codeTable[self::START_C];
			$characterSet = self::CHARSET_C;
			$weightedSum += self::START_C;
		} else if (strpos ($this->charsB, $this->text{0})) {
			// Character set B
			$biteCode['DATA'] .= $this->codeTable[self::START_B];
			$characterSet = self::CHARSET_B;
			$weightedSum += self::START_B;
		} else if (strpos ($this->charsA, $this->text{0})) {
			// Character set A
			$biteCode['DATA'] .= $this->codeTable[self::START_A];
			$characterSet = self::CHARSET_A;
			$weightedSum += self::START_A;
		} else {
			throw new Exception();
		}


		for($i=0;$i<strlen($this->text);$i++) {
			switch ($characterSet) {
				case 'B':
					// Character set B is default, so it is first
					$characterValue = $this->setB[ord($this->text{$i})];
					$biteCode['DATA'] .= $this->codeTable[$characterValue];
					break;

				case 'A':
					$characterValue = $this->setA[ord($this->text{$i})];
					$biteCode['DATA'] .= $this->codeTable[$characterValue];
					break;

				case 'C':
					$characterValue = intval($this->text{$i}.$this->text{$i+1});
					$biteCode['DATA'] .= $this->codeTable[$characterValue];
					$i++;
					break;

				default:
					break;
			}

			$weightedSum += $characterValue*$checksumCounter;
			$checksumCounter++;

			// find next char set.
			if(strlen($this->text) > ($i+2) && is_numeric($this->text{$i+1}) && is_numeric($this->text{$i+2})) {
				if($characterSet!=self::CHARSET_C) {
					$characterValue = 99;
					$biteCode['DATA'] .= $this->codeTable[$characterValue];
					$weightedSum += $characterValue*$checksumCounter;
					$checksumCounter++;
				}
				$characterSet = 'C';
			} else if(isset($this->text{$i+1})) {
				$newCharacterSet = $this->findCharacterSet($this->text{$i+1});
				if($characterSet==self::CHARSET_C) {
					if($newCharacterSet==self::CHARSET_A) {
						$characterValue = 101;
					}
					else {
						$characterValue = 100;
					}
					$weightedSum += $characterValue*$checksumCounter;
					$checksumCounter++;
					$biteCode['DATA'] .= $this->codeTable[$characterValue];
				}
				$characterSet = $newCharacterSet;
			}
		}

		// Count the checksum
		$checkSum = (int) $weightedSum%103;

		// Add the checksum
		$biteCode['DATA'] .= $this->codeTable[$checkSum];

		// Add the stop character
		$biteCode['DATA'] .= $this->codeTable[self::STOP];

		// Add the termination bar
		$biteCode['DATA'] .= $this->codeTable[self::TERMINATION];

		return $biteCode;
	}


	/**
	 * Find Character Set
	 * Find correct character set depends on imput char
	 *
	 * @param char $char
	 * @return char
	 */
	private function findCharacterSet($char) {
		if(strpos($this->charsB, $char)!==false) {
			return self::CHARSET_B;
		}
		if(strpos($this->charsA, $char)!==false) {
			return self::CHARSET_A;
		}
	}

}
