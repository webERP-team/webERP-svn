<?php

/**
 * This file is part of the BarcodePack - PHP Barcode Library.
 * Copyright (c) 2011 Tomáš Horáček (http://www.barcodepack.com)
 * BarcodePack by Tomáš Horáček is licensed under
 * a Creative Commons Attribution-NoDerivs 3.0 Unported License.
 */



// Error codes

define('E_EMPTY_TEXT', 100);
define('E_MODULE_SIZE', 101);


/**
 * barcode
 *
 * Main class of BarcodePack Library
 *
 * @author Tomáš Horáček <info@webpack.cz>
 * @package BarcodePack
 */
class barcode {

	// Minimal module size
	const MIN_MODULE_SIZE = 1;

	// Maximal module size
	const MAX_MODULE_SIZE = 10;

	// Default module size
	const MODULE_SIZE = 2;

	/**
	 * Text to be encoded
	 * @var string
	 */
	protected $text = '';

	/**
	 * Module size in pixels
	 * @var int
	 */
	protected $moduleSize = null;


	/**
	 * Constructor
	 *
	 * @param string $text
	 * @param int $moduleSize
	 */
	public function __construct($text, $moduleSize=self::MODULE_SIZE)
	{

		// input text check
		if(!empty ($text)) {
			$this->text = $text;
		} else {
			throw new Exception('Input text can not be empty.', E_EMPTY_TEXT);
		}

		// Module size check
		$moduleSize = (int) $moduleSize;
		if($moduleSize >= self::MIN_MODULE_SIZE && $moduleSize <= self::MAX_MODULE_SIZE) {
			$this->moduleSize = $moduleSize;
		} else {
			throw new Exception('Module size have to be in range '.self::MIN_MODULE_SIZE.' - '.self::MAX_MODULE_SIZE.'.', E_MODULE_SIZE);
		}

	}

}
