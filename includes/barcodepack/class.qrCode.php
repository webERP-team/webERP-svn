<?php

/**
 * This file is part of the BarcodePack - PHP Barcode Library.
 * Copyright (c) 2011 Tomáš Horáček (http://www.barcodepack.com)
 * BarcodePack by Tomáš Horáček is licensed under
 * a Creative Commons Attribution-NoDerivs 3.0 Unported License.
 */



require_once 'class.barcode.php';

// Erroe codes
define('E_BAD_QR_LENGTH',	800);
define('E_BAD_VERSION',	801);
define('E_BAD_MASK',	802);



/**
 * qrCode
 * Class for QR Code generation
 *
 * @author Tomáš Horáček <info@webpack.cz>
 * @package BarcodePack
 */
class qrCode extends barcode {

	// Error correction levels
	const ECL_L_CODE = 'L';
	const ECL_M_CODE = 'M';
	const ECL_Q_CODE = 'Q';
	const ECL_H_CODE = 'H';

	const DEFAULT_ECL = 'M';

	// Error correction levels numbers
	const ECL_L = 1;
	const ECL_M = 0;
	const ECL_Q = 3;
	const ECL_H = 2;


	// Mode indicators
	const MODE_ECI = 7;
	const MODE_NUMERIC = 1;
	const MODE_ALPHANUMERIC = 2;
	const MODE_8BITBYTE = 4;
	const MODE_KANJI = 8;
	const MODE_STRUCTURED_APPEND = 3;
	const MODE_FNC1_FP = 5; // First position
	const MODE_FNC1_SP = 9; // Second position
	const MODE_TERMINATOR = 0;

	// Quiet zone size
	const QUIET_ZONE = 4;

	// Directions
	const DIRECTION_UP = 'UP';
	const DIRECTION_DOWN = 'DOWN';

	/**
	 * Error correction level
	 * @var char
	 */
	private $ecl = self::ECL_L;

	/**
	 * Version (1-40)
	 * @var int
	 */
	private $version = 1;

	/**
	 * Matrix size (QR code size without quiet zone)
	 * @var int
	 */
	private $matrixSize = 0;

	/**
	 * Symbol size with quiet zone
	 * @var int
	 */
	private $symbolSize = 0;

	/**
	 * Number of imput characters
	 * @var int
	 */
	private $charsNum = 0;

	/**
	 * Mode indicator
	 * @var int
	 */
	private $mode;

	/**
	 * QR code matrix
	 * @var array
	 */
	private $matrix;

	/**
	 * Masked matrix
	 * @var array
	 */
	private $maskedMatrix;

	/**
	 * Coordinates of modules in matrix
	 * @var array
	 */
	private $bitsCoordinates;

	/**
	 * Mask reference (0-7)
	 * @var int
	 */
	private $maskReference;

	/**
	 * ECL conversion table
	 * @var array
	 */
	private $eclConvertTable = array(
		self::ECL_L_CODE => self::ECL_L,
		self::ECL_M_CODE => self::ECL_M,
		self::ECL_Q_CODE => self::ECL_Q,
		self::ECL_H_CODE => self::ECL_H,
	);

	/**
	 * Number of bits the num chars indicator is saved
	 * @var array
	 */
	private $characterCountIndicatorBits = array(
		self::MODE_NUMERIC		=> array(1=> 10, 10, 10, 10, 10, 10, 10, 10, 10, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14,),
		self::MODE_ALPHANUMERIC => array(1=>  9,  9,  9,  9,  9,  9,  9,  9,  9, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13,),
		self::MODE_8BITBYTE		=> array(1=>  8,  8,  8,  8,  8,  8,  8,  8,  8, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16,),
		self::MODE_KANJI		=> array(1=>  8,  8,  8,  8,  8,  8,  8,  8,  8, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 11, 11, 11, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12,),
	);

	/**
	 * Code capacity
	 * Include mode indicator and num chars indicator without ECL
	 * @var array
	 */
	private $numberDataBits = array(
		self::ECL_L => array(1 => 152, 272, 440, 640, 864, 1088, 1248, 1552, 1856, 2192, 2592, 2960, 3424, 3688, 4184, 4712, 5176, 5768, 6360, 6888, 7456, 8048, 8752, 9392, 10208, 10960, 11744, 12248, 13048, 13880, 14744, 15640, 16568, 17528, 18448, 19472, 20528, 21616, 22496, 23648,),
		self::ECL_M => array(1 => 128, 224, 352, 512, 688,  864,  992, 1232, 1456, 1728, 2032, 2320, 2672, 2920, 3320, 3624, 4056, 4504, 5016, 5352, 5712, 6256, 6880, 7312,  8000,  8496,  9024,  9544, 10136, 10984, 11640, 12328, 13048, 13800, 14496, 15312, 15936, 16816, 17728, 18672,),
		self::ECL_Q => array(1 => 104, 176, 272, 384, 496,  608,  704,  880, 1056, 1232, 1440, 1648, 1952, 2088, 2360, 2600, 2936, 3176, 3560, 3880, 4096, 4544, 4912, 5312,  5744,  6032,  6464,  6968,  7288,  7880,  8264,  8920,  9368,  9848, 10288, 10832, 11408, 12016, 12656, 13328,),
		self::ECL_H => array(1 =>  72, 128, 208, 288, 368,  480,  528,  688,  800,  976, 1120, 1264, 1440, 1576, 1784, 2024, 2264, 2504, 2728, 3080, 3248, 3536, 3712, 4112,  4304,  4768,  5024,  5288,  5608,  5960,  6344,  6760,  7208,  7688,  7888,  8432,  8768,  9136,  9776, 10208,),
	);

	/**
	 * Code capacity in codewords
	 * @var array
	 */
	private $codewordsCapacity=array(1 => 26,44,70,100,134,172,196,242,
		292,346,404,466,532,581,655,733,815,901,991,1085,1156,
		1258,1364,1474,1588,1706,1828,1921,2051,2185,2323,2465,
		2611,2761,2876,3034,3196,3362,3532,3706);

	/**
	 * Capacity in characters
	 * @var array
	 */
	private $dataCapacity = array(
		self::ECL_L => array(
			self::MODE_NUMERIC		=> array(1 => 41, 77, 127, 187, 255, 322, 370, 461, 552, 652, 772, 883, 1022, 1101, 1250, 1408, 1548, 1725, 1903, 2061, 2232, 2409, 2620, 2812, 3057, 3283, 3517, 3669, 3909, 4158, 4417, 4686, 4965, 5253, 5529, 5836, 6153, 6479, 6743, 7089,),
			self::MODE_ALPHANUMERIC	=> array(1 => 25, 47,  77, 114, 154, 195, 224, 279, 335, 395, 468, 535, 619, 667, 758, 854, 938, 1046, 1153, 1249, 1352, 1460, 1588, 1704, 1853, 1990, 2132, 2223, 2369, 2520, 2677, 2840, 3009, 3183, 3351, 3537, 3729, 3927, 4087, 4296,),
			self::MODE_8BITBYTE		=> array(1 => 17, 32,  53,  78, 106, 134, 154, 192, 230, 271, 321, 367, 425, 458, 520, 586, 644, 718, 792, 858, 929, 1003, 1091, 1171, 1273, 1367, 1465, 1528, 1628, 1732, 1840, 1952, 2068, 2188, 2303, 2431, 2563, 2699, 2809, 2953,),
			self::MODE_KANJI		=> array(1 => 10, 20,  32,  48, 65, 82, 95, 118, 141, 167, 198, 226, 262, 282, 320, 361, 397, 442, 488, 528, 572, 618, 672, 721, 784, 842, 902, 940, 1002, 1066, 1132, 1201, 1273, 1347, 1417, 1496, 1577, 1661, 1729, 1817,),
		),
		self::ECL_M => array(
			self::MODE_NUMERIC => array(1 => 34, 63, 101, 149, 202, 255, 293, 365, 432, 513, 604, 691, 796, 871, 991, 1082, 1212, 1346, 1500, 1600, 1708, 1872, 2059, 2188, 2395, 2544, 2701, 2857, 3035, 3289, 3486, 3693, 3909, 4134, 4343, 4588, 4775, 5039, 5313, 5596,),
			self::MODE_ALPHANUMERIC => array(1 => 20, 38, 61, 90, 122, 154, 178, 221, 262, 311, 366, 419, 483, 528, 600, 656, 734, 816, 909, 970, 1035, 1134, 1248, 1326, 1451, 1542, 1637, 1732, 1839, 1994, 2113, 2238, 2369, 2506, 2632, 2780, 2894, 3054, 3220, 3391,),
			self::MODE_8BITBYTE => array(1 => 14, 26, 42, 62, 84, 106, 122, 152, 180, 213, 251, 287, 331, 362, 412, 450, 504, 560, 624, 666, 711, 779, 857, 911, 997, 1059, 1125, 1190, 1264, 1370, 1452, 1538, 1628, 1722, 1809, 1911, 1989, 2099, 2213, 2331,),
			self::MODE_KANJI => array(1 => 8, 16, 26, 38, 52, 65, 75, 93, 111, 131, 155, 177, 204, 223, 254, 277, 310, 345, 384, 410, 438, 480, 528, 561, 614, 652, 692, 732, 778, 843, 894, 947, 1002, 1060, 1113, 1176, 1224, 1292, 1362, 1435,),
		),
		self::ECL_Q => array(
			self::MODE_NUMERIC => array(1 => 27, 48, 77, 111, 144, 178, 207, 259, 312, 364, 427, 489, 580, 621, 703, 775, 876, 948, 1063, 1159, 1224, 1358, 1468, 1588, 1718, 1804, 1933, 2085, 2181, 2358, 2473, 2670, 2805, 2949, 3081, 3244, 3417, 3599, 3791, 3993,),
			self::MODE_ALPHANUMERIC => array(1 => 16, 29, 47, 67, 87, 108, 125, 157, 189, 221, 259, 296, 352, 376, 426, 470, 531, 574, 644, 702, 742, 823, 890, 963, 1041, 1094, 1172, 1263, 1322, 1429, 1499, 1618, 1700, 1787, 1867, 1966, 2071, 2181, 2298, 2420,),
			self::MODE_8BITBYTE => array(1 => 11, 20, 32, 46, 60, 74, 86, 108, 130, 151, 177, 203, 241, 258, 292, 322, 364, 394, 442, 482, 509, 565, 611, 661, 715, 751, 805, 868, 908, 982, 1030, 1112, 1168, 1228, 1283, 1351, 1423, 1499, 1579, 1663,),
			self::MODE_KANJI => array(1 => 7, 12, 20, 28, 37, 45, 53, 66, 80, 93, 109, 125, 149, 159, 180, 198, 224, 243, 272, 297, 314, 348, 376, 407, 440, 462, 496, 534, 559, 604, 634, 684, 719, 756, 790, 832, 876, 923, 972, 1024,),
		),
		self::ECL_H => array(
			self::MODE_NUMERIC => array(1 => 17, 34, 58, 82, 106, 139, 154, 202, 235, 288, 331, 374, 427, 468, 530, 602, 674, 746, 813, 919, 969, 1056, 1108, 1228, 1286, 1425, 1501, 1581, 1677, 1782, 1897, 2022, 2157, 2301, 2361, 2524, 2625, 2735, 2927, 3057,),
			self::MODE_ALPHANUMERIC => array(1 => 10, 20, 35, 50, 64, 84, 93, 122, 143, 174, 200, 227, 259, 283, 321, 365, 408, 452, 493, 557, 587, 640, 672, 744, 779, 864, 910, 958, 1016, 1080, 1150, 1226, 1307, 1394, 1431, 1530, 1591, 1658, 1774, 1852,),
			self::MODE_8BITBYTE => array(1 => 7, 14, 24, 34, 44, 58, 64, 84, 98, 119, 137, 155, 177, 194, 220, 250, 280, 310, 338, 382, 403, 439, 461, 511, 535, 593, 625, 658, 698, 742, 790, 842, 898, 958, 983, 1051, 1093, 1139, 1219, 1273,),
			self::MODE_KANJI => array(1 => 4, 8, 15, 21, 27, 36, 39, 52, 60, 74, 85, 96, 109, 120, 136, 154, 173, 191, 208, 235, 248, 270, 284, 315, 330, 365, 385, 405, 430, 457, 486, 518, 553, 590, 605, 647, 673, 701, 750, 784,),
		),
	);

	/**
	 * Position detection pattern
	 * @var array
	 */
	private $positionPattern = array(
		1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 1, 0, 1, 1, 0, 1, 1, 1, 0, 1, 1, 0, 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1,
	);

	/**
	 * Alignment pattern
	 * @var array
	 */
	private $alignmentPattern = array(
		1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 0, 1, 0, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1,
	);

	/**
	 * Coordinates of alignment patterns
	 * @var array
	 */
	private $alignmentPatternCoordinate = array(
		1 => array(),
		array(6, 18),
		array(6, 22),
		array(6, 26),
		array(6, 30),
		array(6, 34),
		array(6, 22, 38),
		array(6, 24, 42),
		array(6, 26, 46),
		array(6, 28, 50),
		array(6, 30, 54),
		array(6, 32, 58),
		array(6, 34, 62),
		array(6, 26, 46, 66,),
		array(6, 26, 48, 70,),
		array(6, 26, 50, 74,),
		array(6, 30, 54, 78,),
		array(6, 30, 56, 82,),
		array(6, 30, 58, 86,),
		array(6, 34, 62, 90,),
		array(6, 28, 50, 72, 94),
		array(6, 26, 50, 74, 98,),
		array(6, 30, 54, 78, 102,),
		array(6, 28, 54, 80, 106),
		array(6, 32, 58, 84, 110,),
		array(6, 30, 58, 86, 114,),
		array(6, 34, 62, 90, 118,),
		array(6, 26, 50, 74, 98, 122,),
		array(6, 30, 54, 78, 102, 126,),
		array(6, 26, 52, 78, 104, 130,),
		array(6, 30, 56, 82, 108, 134,),
		array(6, 34, 60, 86, 112, 138,),
		array(6, 30, 58, 86, 114, 142,),
		array(6, 34, 62, 90, 118, 146,),
		array(6, 30, 54, 78, 102, 126, 150,),
		array(6, 24, 50, 76, 102, 128, 154,),
		array(6, 28, 54, 80, 106, 132, 158,),
		array(6, 32, 58, 84, 110, 136, 162,),
		array(6, 26, 54, 82, 110, 138, 166,),
		array(6, 30, 58, 86, 114, 142, 170),
	);


	/**
	 * Block specification
	 * @var array
	 */
	private $blocksSpec = array(
		1 =>
        array(self::ECL_L => array( 1,  0), self::ECL_M => array( 1,  0), self::ECL_Q => array( 1,  0), self::ECL_H => array( 1,  0)), // 1
		array(self::ECL_L => array( 1,  0), self::ECL_M => array( 1,  0), self::ECL_Q => array( 1,  0), self::ECL_H => array( 1,  0)),
		array(self::ECL_L => array( 1,  0), self::ECL_M => array( 1,  0), self::ECL_Q => array( 2,  0), self::ECL_H => array( 2,  0)),
		array(self::ECL_L => array( 1,  0), self::ECL_M => array( 2,  0), self::ECL_Q => array( 2,  0), self::ECL_H => array( 4,  0)),
		array(self::ECL_L => array( 1,  0), self::ECL_M => array( 2,  0), self::ECL_Q => array( 2,  2), self::ECL_H => array( 2,  2)), // 5
		array(self::ECL_L => array( 2,  0), self::ECL_M => array( 4,  0), self::ECL_Q => array( 4,  0), self::ECL_H => array( 4,  0)),
		array(self::ECL_L => array( 2,  0), self::ECL_M => array( 4,  0), self::ECL_Q => array( 2,  4), self::ECL_H => array( 4,  1)),
		array(self::ECL_L => array( 2,  0), self::ECL_M => array( 2,  2), self::ECL_Q => array( 4,  2), self::ECL_H => array( 4,  2)),
		array(self::ECL_L => array( 2,  0), self::ECL_M => array( 3,  2), self::ECL_Q => array( 4,  4), self::ECL_H => array( 4,  4)),
		array(self::ECL_L => array( 2,  2), self::ECL_M => array( 4,  1), self::ECL_Q => array( 6,  2), self::ECL_H => array( 6,  2)), //10
		array(self::ECL_L => array( 4,  0), self::ECL_M => array( 1,  4), self::ECL_Q => array( 4,  4), self::ECL_H => array( 3,  8)),
		array(self::ECL_L => array( 2,  2), self::ECL_M => array( 6,  2), self::ECL_Q => array( 4,  6), self::ECL_H => array( 7,  4)),
		array(self::ECL_L => array( 4,  0), self::ECL_M => array( 8,  1), self::ECL_Q => array( 8,  4), self::ECL_H => array(12,  4)),
		array(self::ECL_L => array( 3,  1), self::ECL_M => array( 4,  5), self::ECL_Q => array(11,  5), self::ECL_H => array(11,  5)),
		array(self::ECL_L => array( 5,  1), self::ECL_M => array( 5,  5), self::ECL_Q => array( 5,  7), self::ECL_H => array(11,  7)), //15
		array(self::ECL_L => array( 5,  1), self::ECL_M => array( 7,  3), self::ECL_Q => array(15,  2), self::ECL_H => array( 3, 13)),
		array(self::ECL_L => array( 1,  5), self::ECL_M => array(10,  1), self::ECL_Q => array( 1, 15), self::ECL_H => array( 2, 17)),
		array(self::ECL_L => array( 5,  1), self::ECL_M => array( 9,  4), self::ECL_Q => array(17,  1), self::ECL_H => array( 2, 19)),
		array(self::ECL_L => array( 3,  4), self::ECL_M => array( 3, 11), self::ECL_Q => array(17,  4), self::ECL_H => array( 9, 16)),
		array(self::ECL_L => array( 3,  5), self::ECL_M => array( 3, 13), self::ECL_Q => array(15,  5), self::ECL_H => array(15, 10)), //20
		array(self::ECL_L => array( 4,  4), self::ECL_M => array(17,  0), self::ECL_Q => array(17,  6), self::ECL_H => array(19,  6)),
		array(self::ECL_L => array( 2,  7), self::ECL_M => array(17,  0), self::ECL_Q => array( 7, 16), self::ECL_H => array(34,  0)),
		array(self::ECL_L => array( 4,  5), self::ECL_M => array( 4, 14), self::ECL_Q => array(11, 14), self::ECL_H => array(16, 14)),
		array(self::ECL_L => array( 6,  4), self::ECL_M => array( 6, 14), self::ECL_Q => array(11, 16), self::ECL_H => array(30,  2)),
		array(self::ECL_L => array( 8,  4), self::ECL_M => array( 8, 13), self::ECL_Q => array( 7, 22), self::ECL_H => array(22, 13)), //25
		array(self::ECL_L => array(10,  2), self::ECL_M => array(19,  4), self::ECL_Q => array(28,  6), self::ECL_H => array(33,  4)),
		array(self::ECL_L => array( 8,  4), self::ECL_M => array(22,  3), self::ECL_Q => array( 8, 26), self::ECL_H => array(12, 28)),
		array(self::ECL_L => array( 3, 10), self::ECL_M => array( 3, 23), self::ECL_Q => array( 4, 31), self::ECL_H => array(11, 31)),
		array(self::ECL_L => array( 7,  7), self::ECL_M => array(21,  7), self::ECL_Q => array( 1, 37), self::ECL_H => array(19, 26)),
		array(self::ECL_L => array( 5, 10), self::ECL_M => array(19, 10), self::ECL_Q => array(15, 25), self::ECL_H => array(23, 25)), //30
		array(self::ECL_L => array(13,  3), self::ECL_M => array( 2, 29), self::ECL_Q => array(42,  1), self::ECL_H => array(23, 28)),
		array(self::ECL_L => array(17,  0), self::ECL_M => array(10, 23), self::ECL_Q => array(10, 35), self::ECL_H => array(19, 35)),
		array(self::ECL_L => array(17,  1), self::ECL_M => array(14, 21), self::ECL_Q => array(29, 19), self::ECL_H => array(11, 46)),
		array(self::ECL_L => array(13,  6), self::ECL_M => array(14, 23), self::ECL_Q => array(44,  7), self::ECL_H => array(59,  1)),
		array(self::ECL_L => array(12,  7), self::ECL_M => array(12, 26), self::ECL_Q => array(39, 14), self::ECL_H => array(22, 41)), //35
		array(self::ECL_L => array( 6, 14), self::ECL_M => array( 6, 34), self::ECL_Q => array(46, 10), self::ECL_H => array( 2, 64)),
		array(self::ECL_L => array(17,  4), self::ECL_M => array(29, 14), self::ECL_Q => array(49, 10), self::ECL_H => array(24, 46)),
		array(self::ECL_L => array( 4, 18), self::ECL_M => array(13, 32), self::ECL_Q => array(48, 14), self::ECL_H => array(42, 32)),
		array(self::ECL_L => array(20,  4), self::ECL_M => array(40,  7), self::ECL_Q => array(43, 22), self::ECL_H => array(10, 67)),
		array(self::ECL_L => array(19,  6), self::ECL_M => array(18, 31), self::ECL_Q => array(34, 34), self::ECL_H => array(20, 61)),//40
	);

	/**
	 * Alphanumeric code coding table
	 * @var array
	 */
	private $alphanumCodingTable = array(
		'0' => 0,  '1' => 1,  '2' => 2,  '3' => 3,  '4' => 4,  '5' => 5,
		'6' => 6,  '7' => 7,  '8' => 8,  '9' => 9,  'A' => 10, 'B' => 11,
		'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17,
		'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23,
		'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29,
		'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34, 'Z' => 35,
		' ' => 36, '$' => 37, '%' => 38, '*' => 39,	'+' => 40, '-' => 41,
		'.' => 42, '/' => 43, ':' => 44,
	);

	/**
	 * Version information
	 * From ISO/IEC 18004:2000, page 78-79
	 * @var array
	 */
	private $versionInformationStream = array(
		7 => 0x07c94, 0x085bc, 0x09a99, 0x0a4d3, 0x0bbf6, 0x0c762, 0x0d847,
		0x0e60d, 0x0f928, 0x10b78, 0x1145d, 0x12a17, 0x13532, 0x149a6, 0x15683,
		0x168c9, 0x177ec, 0x18ec4, 0x191e1, 0x1afab, 0x1b08e, 0x1cc1a, 0x1d33f,
		0x1ed75, 0x1f250, 0x209d5, 0x216f0, 0x228ba, 0x2379f, 0x24b0b, 0x2542e,
		0x26a64, 0x27541, 0x28c69,
	);

	/**
	 * Galois field
	 * @var array
	 */
	private $galoisField;

	/**
	 * Galois field with changed index and value
	 * @var array
	 */
	private $indexGaloisField;


	/**
	 * Constructor
	 *
	 * @param string $text
	 * @param int $moduleSize
	 * @param char $ecl
	 */
	public function __construct($text, $moduleSize=parent::MODULE_SIZE, $ecl=self::ECL_L_CODE, $version=null)
	{
		try {
			parent::__construct($text, $moduleSize);

			// Convert input text to UTF-8
			$current_encoding = mb_detect_encoding($this->text, 'auto');
			$this->text = iconv($current_encoding, 'UTF-8', $this->text);

			$this->ecl = $this->eclConvertTable[$ecl];

			// Num of input chars
			$this->charsNum = strlen($text);

			$this->mode = $this->getMode($text);

			// Mask reference
			$this->maskReference = rand(0,7);


			// Version
			$this->version = $this->getVersion($this->charsNum, $this->ecl, $this->mode, $version);

			// Code size
			$this->matrixSize = $this->getMatrixSize($this->version);

			$this->symbolSize = $this->matrixSize + 2 * self::QUIET_ZONE;


			$this->countGaloisField();

			// Init matrixes
			$this->init();

			$this->bitsCoordinates = $this->getBitsCoordinates($this->maskedMatrix);

			$this->convertData();

			// Format info
			$formatInformation = $this->formatInformation($this->ecl,$this->maskReference);
			$this->matrix = $this->addFormatInformation($this->matrix, $formatInformation);

			// Version info
			if($this->version>=7 && $this->version<=40) {
				$versionInformation = $this->versionInformation($this->version);
				$this->matrix = $this->addVersionInformation($this->matrix, $versionInformation);
			}


		} catch (Exception $e) {
			throw $e;
		}
	}


	/**
	 * Get Mode
	 * Returns mode indicator
	 *
	 * @param string $text
	 * @return int
	 */
	private function getMode($text)
	{
		if (preg_match('/[^0-9]/', $text)==0) {
			return self::MODE_NUMERIC;
		} else if(preg_match('/[^0-9A-Z \$\*\%\+\.\/\:\-]/', $text)==0) {
			return self::MODE_ALPHANUMERIC;
		} else {
			return self::MODE_8BITBYTE;
		}
	}


	/**
	 * Get Version
	 *
	 * @param int $charsNum
	 * @param int $ecl
	 * @param int $mode
	 * @return int
	 */
	private function getVersion($charsNum, $ecl, $mode, $version)
	{
		$findversion = 1;
		while ($findversion<=40) {
			if ($this->dataCapacity[$ecl][$mode][$findversion] >= $charsNum) {
				break;
			}
			$findversion++;
		}

		if($findversion==41) {
			throw new Exception('Input text is too long.', E_BAD_QR_LENGTH);
		}


		if($version) {
			if($findversion<=$version && $version <= 40) {
				return $version;
			} else {
				throw new Exception('Selected version can not be choosen.', E_BAD_VERSION);
			}
		}

		return $findversion;
	}


	/**
	 * Get Matrix Size
	 *
	 * Use bit shift
	 * x << y = x*(2^y)
	 *
	 * @param int $version
	 * @return int
	 */
	private function getMatrixSize($version)
	{
		return 17 + ($version << 2);
	}


	/**
	 * Init
	 *
	 * Matrix init
	 * prepare matrixes and bits
	 *
	 * @return void
	 */
	private function init() {
		for ($y = 0; $y < $this->matrixSize; $y++) {
			for ($x = 0; $x < $this->matrixSize; $x++) {
				$matrix[$y][$x] = 0; // White as default

				// Vertical synch.
				if ($y == 6 && $x % 2 == 0) {
					$matrix[$y][$x] = 1;
				}
				// Hor. synch
				if ($x == 6 && $y % 2 == 0) {
					$matrix[$y][$x] = 1;
				}

				// Mask synch. patterns
				if ($y == 6 || $x == 6) {
					$maskedMatrix[$y][$x] = 1;
				}


				// Format and version mask
				// all versions
				if($x==8 && ($y<=8 || $y>=($this->matrixSize-8))) {
					$maskedMatrix[$y][$x] = 1;
				}
				if($y==8 && ($x<=8 || $x>=($this->matrixSize-8))) {
					$maskedMatrix[$y][$x] = 1;
				}
				// Version >= 7
				if($this->version >= 7) {
					if($y<=5 && $x>=$this->matrixSize-11 && $x<=$this->matrixSize-9) {
						$maskedMatrix[$y][$x] = 1;
					}
					if($x<=5 && $y>=$this->matrixSize-11 && $y<=$this->matrixSize-9) {
						$maskedMatrix[$y][$x] = 1;
					}
				}


				// left top position pattern
				if ($y <= 6 && $x <= 6) {
					$shift = $y * 7 + $x;
					$matrix[$y][$x] = $this->positionPattern[$shift];

					$maskedMatrix[$y][$x] = 1;
					$maskedMatrix[$y+1][$x+1] = 1; // mask. separatoru
				}
				// left bottom position pattern
				if ($y < 7 && $x < $this->matrixSize && $x > ($this->matrixSize - 8)) {
					$shift = $y * 7 + ($this->matrixSize - $x - 1);
					$matrix[$y][$x] = $this->positionPattern[$shift];

					$maskedMatrix[$y][$x] = 1;
					$maskedMatrix[$y+1][$x-1] = 1;
				}
				// right top position pattern
				if ($x < 7 && $y < $this->matrixSize && $y > ($this->matrixSize - 8)) {
					$shift = $x + ($this->matrixSize - $y - 1) * 7;
					$matrix[$y][$x] = $this->positionPattern[$shift];

					$maskedMatrix[$y][$x] = 1;
					$maskedMatrix[$y-1][$x+1] = 1;
				}
			}
		}

		// Alignment patterns
		if (count($this->alignmentPatternCoordinate[$this->version]) > 0) {
			foreach ($this->alignmentPatternCoordinate[$this->version] as $y) {
				foreach ($this->alignmentPatternCoordinate[$this->version] as $x) {
					if (!(($x < 7 && $y < 7) || ($y > ($this->matrixSize - 8) && $x < 7) || ($x > ($this->matrixSize - 8) && $y < 7))) {
						for ($i = 0; $i < 5; $i++) {
							for ($j = 0; $j < 5; $j++) {
								$xCoor = $x - $i + 2;
								$yCoor = $y - $j + 2;
								$matrix[$xCoor][$yCoor] = $this->alignmentPattern[$i * 5 + $j];
								$maskedMatrix[$xCoor][$yCoor] = 1; // Mask alignment patterns
							}
						}
					}
				}
			}
		}

		// Module around detection patterns
		$maskedMatrix[0][7] = 1;
		$maskedMatrix[0][$this->matrixSize-8] = 1;
		$maskedMatrix[7][0] = 1;
		$maskedMatrix[7][$this->matrixSize-1] = 1;
		$maskedMatrix[$this->matrixSize-8][0] = 1;
		$maskedMatrix[$this->matrixSize-1][7] = 1;


		$this->matrix = $matrix;
		$this->maskedMatrix = $maskedMatrix;
	}


	/**
	 * Get Bits Coordinates
	 *
	 * @param array $maskedMatrix
	 * @return array
	 */
	private function getBitsCoordinates($maskedMatrix) {


		// start at right bottom corner
		$y = $this->matrixSize-1;
		$x = $this->matrixSize-1;

		// top
		$direction = self::DIRECTION_UP;

		// and go left
		$goLeft = 1;

		$biteCounter = 0;
		while($biteCounter < $this->codewordsCapacity[$this->version]*8) {

			// jump over timing pattern
			if($x==6) {
				$x=5;
			}

			switch($direction) {
				case self::DIRECTION_UP:
						if(!$this->maskedMatrix[$y][$x]) {
							$bitesCoordinates[$biteCounter] = array($y,$x);
							$biteCounter++;
						}

						// next coordinate
						if($goLeft==1) {
							// Jdi doleva
							$x = $x-1;
							$y = $y;
							$goLeft = 0;
						} else {
							// top right
							$x = $x+1;
							$y = $y-1;

							if($y<0) {
								// direction change
								$direction = self::DIRECTION_DOWN;
								$y = 0;
								$x = $x-2;
							}

							$goLeft = 1;
						}


					break;
				case self::DIRECTION_DOWN:
						if(!$this->maskedMatrix[$y][$x]) {
							$bitesCoordinates[$biteCounter] = array($y,$x);
							$biteCounter++;
						}

						// next coordinate
						if($goLeft==1) {
							// Doleva
							$x = $x-1;
							$y = $y;
							$goLeft = 0;
						} else {
							// down and right
							$x = $x+1;
							$y = $y+1;

							if($y>=$this->matrixSize) {
								// direction change
								$direction = self::DIRECTION_UP;
								$y = $y-1;
								$x = $x-2;
							}

							$goLeft = 1;
						}

					break;
			}
		}

		return $bitesCoordinates;
	}



	/**
	 * Convert Data
	 *
	 * @return void
	 */
	private function convertData()
	{
		$text = $this->text;


		/* DATA CODING ********************************************************/

		$dataCounter = 0;
		// Mode indicator 4b
		$data[$dataCounter] = array(
			4,
			$this->mode,
		);
		$totalDataBits = 4;
		$dataCounter++;

		// Num chars indicator
		$data[$dataCounter] = array(
			// length indicator in bits
			$this->characterCountIndicatorBits[$this->mode][$this->version],
			$this->charsNum,
		);
		$totalDataBits += $this->characterCountIndicatorBits[$this->mode][$this->version];
		$dataCounter++;

		switch($this->mode) {
			case self::MODE_NUMERIC:

				// divide to group with 3 nums
				while (($len = strlen($text)) > 0) {
					if($len < 3) {
						switch($len % 3) {
							case 1:
								$data[] = array(
									4,
									intval(substr($text, 0, 1)),
								);
								$totalDataBits += 4;
								$text = substr($text, 1);
								break;
							case 2:
								$data[] = array(
									7,
									intval(substr($text, 0, 2)),
								);
								$totalDataBits += 7;
								$text = substr($text, 2);
								break;
						}
					} else {
						$data[] = array(
							10,
							intval(substr($text, 0, 3)),
						);
						$totalDataBits += 10;
						$text = substr($text, 3);
					}
				}

				break;

			case self::MODE_ALPHANUMERIC:

				// Conversion to num value
				$i=0;
				while($i<$this->charsNum) {
					if($i%2==0) {
						$data[$dataCounter] = array(
							6,
							$this->alphanumCodingTable[$text{$i}],
						);
						$addToTotalData = 6;
					} else {
						$data[$dataCounter] = array(
							11,
							$data[$dataCounter][1]*45 + $this->alphanumCodingTable[$text{$i}],

						);
						$totalDataBits += 11;
						$addToTotalData = 0;
						$dataCounter++;
					}

					$i++;
				}

				$totalDataBits += $addToTotalData;

				break;

			case self::MODE_8BITBYTE:

					for($i=0;$i<$this->charsNum;$i++) {
						$data[] = array(
							8,
							ord($text{$i}),
						);
						$totalDataBits += 8;
					}

					break;

			default:
				break;
		}

		// add ending 0 and align to 8b
		$totalDataBits += 4;
		$padding = 8-($totalDataBits%8);
		$data[] = array(
			4+$padding,
			0
		);


		/* DIVIDE TO 8B CODEWORDS *********************************************/

		$codewordCounter = 0;
		$dataCounter = 0;
		$dataItems = count($data);

		$dataBuffer = $data[$dataCounter][1];
		$dataBitsCount = $data[$dataCounter][0];

		$remainingBits = 8;

		while($dataCounter < $dataItems) {
			if($codewordCounter==$totalDataBits/8)
				break;

			if($remainingBits >= $dataBitsCount) {
				// OR a ( BITE SHIFT AND 255)
				// AND delete all bits > 255
				$dataCodewords[$codewordCounter] |=	($dataBuffer << ($remainingBits-$dataBitsCount)) & 255;

				// Výpočet zbývajících bitů
				if(($remainingBits -= $dataBitsCount)==0) {
					$codewordCounter++;	// next CW
					$remainingBits = 8;	// remain 8b
				}
				$dataCounter++;	// next data unit
				$dataBuffer = $data[$dataCounter][1];
				$dataBitsCount = $data[$dataCounter][0];
			} else {
				$dataCodewords[$codewordCounter] |=	($dataBuffer >> ($dataBitsCount-$remainingBits)) & 255;
				$dataBitsCount = $dataBitsCount-$remainingBits;
				$codewordCounter++;	// next CW
				$remainingBits = 8;
			}
		}


		/* ADD ALIGNENT CODEWORDS *********************************************/
		$i = 0;
		while($codewordCounter < $this->numberDataBits[$this->ecl][$this->version]/8) {
			if ($i%2==0) {
				$dataCodewords[$codewordCounter]=236;
			} else {
				$dataCodewords[$codewordCounter]=17;
			}
			$i++;
			$codewordCounter++;
		}


		/* DIVIDE INTO BLOCKS *************************************************/

		// number of data and correction blocks
		$numDataBlocks = $this->blocksSpec[$this->version][$this->ecl][0] + $this->blocksSpec[$this->version][$this->ecl][1];
		$numEcBlocks = $numDataBlocks;

		// number of codewords/block
		$dataCodewordsPerBlock = ($this->numberDataBits[$this->ecl][$this->version]/8 - $this->blocksSpec[$this->version][$this->ecl][1])/($numDataBlocks);
		$ecCodewordsPerBlock = ($this->codewordsCapacity[$this->version] - $this->numberDataBits[$this->ecl][$this->version]/8) / $numEcBlocks;

		$dataBlocks = array();
		$ecBlocks = array();
		for($i=0;$i<$numDataBlocks;$i++) {
			// fill data blocks
			if($i<$this->blocksSpec[$this->version][$this->ecl][0]) {
				for($j=0;$j<$dataCodewordsPerBlock;$j++) {
					$dataBlocks[$i][] = array_shift($dataCodewords);
				}
			} else {
				for($j=0;$j<$dataCodewordsPerBlock+1;$j++) {
					$dataBlocks[$i][] = array_shift($dataCodewords);
				}
			}
			// Error correction blocks
			$ecBlocks[$i] = $this->reedSolomon($dataBlocks[$i], $ecCodewordsPerBlock);
		}



		/* INSERT DATA AND ECC INTO MATRIX ************************************/

		// Data blocks
		$bitCounter = 0;
		for($i=0;$i<$dataCodewordsPerBlock;$i++) {
			for($j=0;$j<$numDataBlocks;$j++) {
				for($k=0;$k<8;$k++) {
					$y = $this->bitsCoordinates[$bitCounter][0];
					$x = $this->bitsCoordinates[$bitCounter][1];
					$this->matrix[$y][$x] = (($dataBlocks[$j][$i] & (128>>$k)) ? 1 : 0) ^ $this->mask($y,$x);
					$bitCounter++;
				}
			}
		}

		// insert addional data codewords
		for($j=$this->blocksSpec[$this->version][$this->ecl][0];$j<$numDataBlocks;$j++) {
			for($k=0;$k<8;$k++) {
				$y = $this->bitsCoordinates[$bitCounter][0];
				$x = $this->bitsCoordinates[$bitCounter][1];
				$this->matrix[$y][$x] = (($dataBlocks[$j][$i] & (128>>$k)) ? 1 : 0) ^ $this->mask($y,$x);
				$bitCounter++;
			}
		}

		// Append ECC blocks
		for($i=0;$i<$ecCodewordsPerBlock;$i++) {
			for($j=0;$j<$numEcBlocks;$j++) {
				for($k=0;$k<8;$k++) {
					$y = $this->bitsCoordinates[$bitCounter][0];
					$x = $this->bitsCoordinates[$bitCounter][1];
					$this->matrix[$y][$x] = (($ecBlocks[$j][$i] & (128>>$k)) ? 1 : 0) ^ $this->mask($y,$x);
					$bitCounter++;
				}
			}
		}

	}





	// Mask functions
	private function mask0($y, $x) { return (($x+$y)%2)==0 ? 1 : 0;	}
	private function mask1($y, $x) { return ($y%2)==0 ? 1 : 0;		}
	private function mask2($y, $x) { return ($x%3)==0 ? 1 : 0;		}
	private function mask3($y, $x) { return (($x+$y)%3)==0 ? 1 : 0;	}
	private function mask4($y, $x) { return (((int)($y/2)+(int)($x/3))%2)==0 ? 1 : 0; }
	private function mask5($y, $x) { return (($x*$y)%2 + ($x*$y)%3)==0 ? 1 : 0;	}
	private function mask6($y, $x) { return ((($x*$y)%2 + ($x*$y)%3)%2)==0 ? 1 : 0;	}
	private function mask7($y, $x) { return ((($x*$y)%3 + ($x+$y)%2)%2)==0 ? 1 : 0; }


	/**
	 * Mask
	 * Use mask function depends on reference
	 *
	 * @param int $y
	 * @param int $x
	 * @return int
	 */
	private function mask($y, $x)
	{
		switch($this->maskReference) {
			case 0:
				return $this->mask0($y, $x);
				break;
			case 1:
				return $this->mask1($y, $x);
				break;
			case 2:
				return $this->mask2($y, $x);
				break;
			case 3:
				return $this->mask3($y, $x);
				break;
			case 4:
				return $this->mask4($y, $x);
				break;
			case 5:
				return $this->mask5($y, $x);
				break;
			case 6:
				return $this->mask6($y, $x);
				break;
			case 7:
				return $this->mask7($y, $x);
				break;
			default:
				throw new Exception('Chyba: špatná reference masky.', E_BAD_MASK);
				break;
		}
	}



	/**
	 * Format Information
	 *
	 *
	 * Based on libqrencode C library distributed under LGPL 2.1
	 * Copyright (C) 2006, 2007, 2008, 2009 Kentaro Fukuchi <fukuchi@megaui.net>
	 *
	 * @param int $eclIndicator
	 * @param int $maskReference
	 * @return int
	 */
	private function formatInformation($eclIndicator, $maskReference)
	{
		$data = ($eclIndicator << 13) | ($maskReference << 10);
		$ecc = $data;
		$b = 1 << 14;
		for($i=0; $b != 0; $i++) {
			if($ecc & $b) break;
			$b = $b >> 1;
		}
		$c = 4 - $i;
		$code = 0x537 << $c ; //10100110111
		$b = 1 << (10 + $c);
		for($i=0; $i<=$c; $i++) {
			if($b & $ecc) {
				$ecc ^= $code;
			}
			$code = $code >> 1;
			$b = $b >> 1;
		}

		return ($data | $ecc) ^ 0x5412;
	}


	/**
	 * Add Format Information
	 *
	 * @param array $matrix
	 * @param bitstream $formatInformation
	 * @return array
	 */
	private function addFormatInformation($matrix, $formatInformation)
	{
		// Add one dark module to...
		$matrix[$this->matrixSize-8][8] = 1;

		for($i=0;$i<15;$i++) {
			// bit value
			$value = ($formatInformation & (1<<$i)) ? 1 : 0;

			// vertical format informations
			$y = ($i<=7) ? (($i>5) ? $i+1 : $i) : $this->matrixSize-15+$i;
			$matrix[$y][8] = $value;

			// horizontal format informations
			if($i<=7) {
				$matrix[8][$this->matrixSize-$i-1] = $value;
			} else if($i==8) {
				$matrix[8][7] = $value;
			} else {
				$matrix[8][15-$i-1] = $value;
			}
		}

		return $matrix;
	}


	/**
	 * Version Information
	 *
	 * @param int $version
	 * @return int
	 */
	private function versionInformation($version)
	{
		$verson = intval($version);
		if($version >=7 && $version<=40) {
			return $this->versionInformationStream[$version];
		} else {
			throw new Exception('Selected version can not be choosen', E_BAD_VERSION);
		}
	}


	/**
	 * Add Version Information
	 * @param array $matrix
	 * @param bitestream $versionInformation
	 * @return array
	 */
	private function addVersionInformation($matrix, $versionInformation)
	{
		if($this->version>=7 && $this->version <=40) {
			// left bottom
			$counter = 0;
			for($i=0;$i<6;$i++) {
				for($j=0;$j<3;$j++) {
					$matrix[$this->matrixSize-11+$j][$i] = ($versionInformation & (1<<$counter)) ? 1 : 0;
					$counter++;
				}
			}
			// right top
			$counter = 0;
			for($i=0;$i<6;$i++) {
				for($j=0;$j<3;$j++) {
					$matrix[$i][$this->matrixSize-11+$j] = ($versionInformation & (1<<$counter)) ? 1 : 0;
					$counter++;
				}
			}

			return $matrix;
		} else {
			return $matrix;
		}
	}


	/**
	 * Count Galois Field
	 *
	 * more info at http://eduramble.org/rs2/galois.html
	 *
	 * @return void
	 */
	private function countGaloisField()
	{
		$pp = 285;	// primitive polynomial

		// fill fields
		$galoisField = array_fill(0, 255, 0);
		$indexGaloisField = array_fill(0, 255, 0);

		// first members
		$x=1;
		$galoisField[0]=$x;
		$indexGaloisField[1]=0;
		$galoisField[255]=0;
		$indexGaloisField[0]=255;

		for($i=1;$i<255;$i++) {
			$x=$x*2;
			if ($x>255) {
				$x = $x ^ $pp;
			}
			$galoisField[$i]=$x;
			$indexGaloisField[$x]=$i;
		}

		$this->galoisField = $galoisField;
		$this->indexGaloisField = $indexGaloisField;
	}


	/**
	 * Count Generator Polynomials
	 * Count pollinomial needed for cound reed solomon
	 *
	 * Based on similar function in libqrencode
	 * (http://fukuchi.org/works/qrencode/index.en.html)
	 *
	 * @param int $numEcc
	 * @return array
	 */
	private function countGeneratorPolynomials($numEcc)
	{
		$genpoly[0] = 1;
		for ($i = 0; $i < $numEcc; $i++) {
			$genpoly[$i+1] = 1;
			for ($j = $i; $j > 0; $j--) {
				if ($genpoly[$j] != 0) {
					$genpoly[$j] = $genpoly[$j-1] ^ $this->galoisField[($this->indexGaloisField[$genpoly[$j]] + $i)%255];
				} else {
					$genpoly[$j] = $genpoly[$j-1];
				}
			}
			$genpoly[0] = $this->galoisField[($this->indexGaloisField[$genpoly[0]] + $i)%255];
		}

		// Index equivalent
		for ($i = 0; $i <= $numEcc; $i++) {
			$genpoly[$i] = $this->indexGaloisField[$genpoly[$i]];
		}

		return $genpoly;
	}


	/**
	 * Reed Solomon
	 * Count ECL
	 *
	 * @param array $dataCodewords
	 * @param int $numEcc Number od ECL
	 * @return array
	 */
	private function reedSolomon($dataCodewords, $numEcc)
	{
		$generatorPolynomials = $this->countGeneratorPolynomials($numEcc);

		// Num of iterations
		$mainIterationCount = count($dataCodewords);
		$subIterationCount = count($generatorPolynomials);

		$ecc = array_fill(0, $subIterationCount-1, 0);

		for($i=0; $i< $mainIterationCount; $i++) {
			$feedback = $this->indexGaloisField[$dataCodewords[$i] ^ $ecc[0]];
			if($feedback != 255) {
				$feedback = (255 - $generatorPolynomials[$subIterationCount-1] + $feedback)%255;
				for($j=1;$j<$subIterationCount-1;$j++) {
					$ecc[$j] ^= $this->galoisField[($feedback + $generatorPolynomials[$subIterationCount-1-$j])%255];
				}
			}
			array_shift($ecc);	// Shift
			if($feedback != 255) {
				array_push($ecc, $this->galoisField[($feedback + $generatorPolynomials[0])%255]);
			} else {
				array_push($ecc, 0);
			}
		}

		return $ecc;
	}






	/**
	 * Draw
	 *
	 * @return image resource
	 */
	public function draw() {

		// Create image
		$im = imageCreate($this->matrixSize, $this->matrixSize);

		// Colours used in Barcode
		$color = array(
			Imagecolorallocate($im, 255, 255, 255), // white
			Imagecolorallocate($im, 0, 0, 0), // black
		);

		for ($y = 0; $y < $this->matrixSize; $y++) {
			for ($x = 0; $x < $this->matrixSize; $x++) {
				imagesetpixel($im, $x, $y, $color[$this->matrix[$y][$x]]);
			}
		}

		$dimension = $this->symbolSize * $this->moduleSize;
		$dimensionNoQuiet = $this->matrixSize * $this->moduleSize;


		$out = ImageCreate($dimension, $dimension);
		Imagecolorallocate($out, 255, 255, 255);

		$move = self::QUIET_ZONE * $this->moduleSize;

		// resize image and add quiet zone
		imagecopyresized($out, $im, $move, $move, 0, 0, $dimensionNoQuiet, $dimensionNoQuiet, $this->matrixSize, $this->matrixSize);

		return $out;
	}



	/**
	 * Raw Data
	 *
	 * @return string $output
	 */
	public function rawData()
	{
		$output = "";
		for ($y = 0; $y < $this->matrixSize; $y++) {
			for ($x = 0; $x < $this->matrixSize; $x++) {
				$output .= $this->matrix[$y][$x];
			}
			$output .= "\n";
		}

		return $output;
	}



}
