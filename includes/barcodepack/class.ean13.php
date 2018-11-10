<?php

require_once 'class.linearBarcode.php';

// Error Codes
define('E_BAD_EAN_LENGTH', 600);



/**
 * EAN 13
 *
 * @author Tomáš Horáček <info@webpack.cz>
 * @package BarcodePack
 */
class ean13 extends linearBarcode {

	/** @var array */
	private $allowedChars = array(
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
	);

	/** @var array */
	private $parity = array(
		'0' => 'LLLLLLRRRRRR',
		'1' => 'LLGLGGRRRRRR',
		'2' => 'LLGGLGRRRRRR',
		'3' => 'LLGGGLRRRRRR',
		'4' => 'LGLLGGRRRRRR',
		'5' => 'LGGLLGRRRRRR',
		'6' => 'LGGGLLRRRRRR',
		'7' => 'LGLGLGRRRRRR',
		'8' => 'LGLGGLRRRRRR',
		'9' => 'LGGLGLRRRRRR',
	);


	/**
	 * Zero represents white line
	 * One represents black line
	 *
	 * After each character except STOP I will add one white line
	 * (separating line)
	 *
	 * @var array
	 */
	private $codeTable = array(
		'0' => array('L'=>'0001101', 'G'=>'0100111', 'R'=>'1110010',),
		'1' => array('L'=>'0011001', 'G'=>'0110011', 'R'=>'1100110',),
		'2' => array('L'=>'0010011', 'G'=>'0011011', 'R'=>'1101100',),
		'3' => array('L'=>'0111101', 'G'=>'0100001', 'R'=>'1000010',),
		'4' => array('L'=>'0100011', 'G'=>'0011101', 'R'=>'1011100',),
		'5' => array('L'=>'0110001', 'G'=>'0111001', 'R'=>'1001110',),
		'6' => array('L'=>'0101111', 'G'=>'0000101', 'R'=>'1010000',),
		'7' => array('L'=>'0111011', 'G'=>'0010001', 'R'=>'1000100',),
		'8' => array('L'=>'0110111', 'G'=>'0001001', 'R'=>'1001000',),
		'9' => array('L'=>'0001011', 'G'=>'0010111', 'R'=>'1110100',),
		'START' => '101',
		'SEPARATOR' => '01010',
		'STOP' => '101',
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

			if(strlen($this->text)!=12) {
				throw new Exception('Text length must be 12 characters.', E_BAD_EAN_LENGTH);
			}

			$this->biteCode = $this->createBiteCode();
		}
		catch(Exception $e) {
			throw $e;
		}

	}


	/**
	 * Create Bite Code
	 * Create bitecode where 1 represents dark module and 0 white module.
	 *
	 * @return string
	 */
	private function createBiteCode()
	{
		$biteCode = array();

		$saveTo = 'DATA';

		// Parity determine
		$parity = $this->parity[$this->text{0}];

		$biteCode['START'] = $this->codeTable['START'];

		for($i=1;$i<strlen($this->text);$i++) {
			$biteCode[$saveTo] .= $this->codeTable[$this->text{$i}][$parity{$i-1}];
			if($i==6) {
				$biteCode['SEPARATOR'] = $this->codeTable['SEPARATOR'];
				$saveTo = 'DATA2';
			}

		}

		$checksum = (string) $this->checksum($this->text);

		$this->text .= $checksum;

		$biteCode[$saveTo] .= $this->codeTable[$checksum]['R'];

		$biteCode['STOP'] = $this->codeTable['STOP'];

		return $biteCode;
	}


	/**
	 * Checksum
	 * Count checksum
	 *
	 * @param string $text
	 * @return int
	 */
	private function checksum($text) {

		$evensum = 0;
		$oddsum = 0;

		for($i=1;$i<=strlen($text);$i++) {
			if($i%2==0) {
				$evensum += (int) $text{$i-1};
			} else {
				$oddsum += (int) $text{$i-1};
			}
		}

		$sum = $evensum*3 + $oddsum;

		return ceil($sum/10)*10 - $sum;
	}


	/**
	 * Draw
	 * Add text into barcode
	 *
	 * @param bool $showText
	 * @return image resource
	 */
	public function draw($showText = true) {
		$im = parent::draw(false);

		$margin = $this->margin*$this->moduleSize;


		$white = Imagecolorallocate ($im,255,255,255);
		$black = Imagecolorallocate ($im,0,0,0);


		if($showText) {

			// Increase space between symbol 2x
			$im2 = ImageCreate($this->getBarcodeLen()*$this->moduleSize+(2*$margin)+$margin,
				$this->height+$this->fontSize+(2*$margin));

			imagecopy($im2, $im, $margin, 0, 0, 0, $this->getBarcodeLen()*$this->moduleSize+(2*$margin), $this->height+$this->fontSize+(2*$margin));

			// Divide text into three parts and each insert to the diffrerent place
			$charsA = $this->text{0};	// first char
			for($i=1;$i<=strlen($this->text);$i++) {
				if($i<=6) {
					$charsB .= $this->text{$i};
				} else {
					$charsC .= $this->text{$i};
				}
			}

			// Insert A
			$textWidth = ImageFontWidth($this->fontSize)*strlen($charsA);
			imagestring ($im2, $this->fontSize,
					$margin,
					$this->height-$this->fontSize/2+$margin, $charsA, $black);

			// Insert B
			$textWidth = ImageFontWidth($this->fontSize)*strlen($charsB);
			imagestring ($im2, $this->fontSize,
					$this->getBarcodeLen()*$this->moduleSize/4-$textWidth/2+2*$margin,
					$this->height-$this->fontSize/2+$margin, $charsB, $black);

			// Insert C
			$textWidth = ImageFontWidth($this->fontSize)*strlen($charsC);
			imagestring ($im2, $this->fontSize,
					$this->getBarcodeLen()*$this->moduleSize-$this->getBarcodeLen()*$this->moduleSize/4-$textWidth/2+2*$margin,
					$this->height-$this->fontSize/2+$margin, $charsC, $black);
		}

		return $im2;
	}

}
