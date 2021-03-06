<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return array(

	/**
	 * A couple of named patterns that are often used
	 */
	'patterns' => array(
		'local'		 => '%c',

		'mysql'		 => '%Y-%m-%d %H:%M:%S',
		'mysql_date' => '%Y-%m-%d',

		'us'		 => '%m/%d/%Y',
		'us_short'	 => '%m/%d',
		'us_named'	 => '%B %d %Y',
		'us_full'	 => '%I:%M %p, %B %d %Y',
		'us_full_tz'	 => '%I:%M %p, %B %d %Y %Z',
		'us_full_sec'	 => '%I:%M:%S %p, %B %d %Y',
		'us_full_sec_tz'	 => '%I:%M:%S %p, %B %d %Y %Z',
		'eu'		 => '%d/%m/%Y',
		'eu_short'	 => '%d/%m',
		'eu_named'	 => '%d %B %Y',
		'eu_full'	 => '%H:%M, %d %B %Y',
		'eu_full_tz'	 => '%H:%M, %d %B %Y %Z',
		'eu_full_sec'	 => '%H:%M:%S, %d %B %Y',
		'eu_full_sec_tz'	 => '%H:%M:%S, %d %B %Y %Z',
		'ja'		 => '%Y/%m/%d',
		'ja_short'	 => '%m月%d日',
		'ja_named'	 => '%Y年%m月%d日',
		'ja_full'	 => '%Y年%m月%d日 %H:%M',
		'ja_full_tz'	 => '%Y年%m月%d日 %H:%M %Z',
		'ja_full_sec'	 => '%Y年%m月%d日 %H:%M:%S',
		'ja_full_sec_tz'	 => '%Y年%m月%d日 %H:%M:%S %Z',

		'24h'		 => '%H:%M',
		'12h'		 => '%I:%M %p',
	),
);
