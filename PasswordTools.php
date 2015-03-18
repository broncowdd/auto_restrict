<?php 
# ------------------ BEGIN LICENSE BLOCK ------------------
#
# This file is part of ORDONNANCIER
#
# Copyright (c) 2009 - 2014 Cyril MAGUIRE, <contact@ecyseo.net>
# Licensed under the CeCILL v2.1 license.
# See http://www.cecill.info/licences.fr.html
#
# ------------------- END LICENSE BLOCK -------------------

namespace SIGesTH\core\lib;

/**
 * @package    ORDONNANCIER
 * @author     MAGUIRE Cyril <contact@ecyseo.net>
 * @copyright  2009-2014 Cyril MAGUIRE, <contact@ecyseo.net>
 * @license    Licensed under the CeCILL v2.1 license. http://www.cecill.info/licences.fr.html
 */
class PasswordTools {

	private static function options() {
		return array(
		    'cost' => self::getOptimalBcryptCostParameter(),
		    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
		);
	}

	/**
	 * This code will benchmark your server to determine how high of a cost you can
	 * afford. You want to set the highest cost that you can without slowing down
	 * you server too much. 8-10 is a good baseline, and more is good if your servers
	 * are fast enough. The code below aims for ≤ 50 milliseconds stretching time,
	 * which is a good baseline for systems handling interactive logins.
	 * @Param int $min_ms Minimum amount of time in milliseconds that it should take
	 * to calculate the hashes
	 */
	private static function getOptimalBcryptCostParameter($timeTarget = 0.25) {// 250 milliseconds 
		$cost = 8;
		do {
		    $cost++;
		    $start = microtime(true);
		    \password_hash("rasmuslerdorf", PASSWORD_DEFAULT, ["cost" => $cost, 'salt' => 'usesomesillystringforsalt']);
		    $end = microtime(true);
		} while (($end - $start) < $timeTarget);

		return $cost;
	}

	/**
	 * Note that the salt here is randomly generated.
	 * Never use a static salt or one that is not randomly generated.
	 *
	 * For the VAST majority of use-cases, let password_hash generate the salt randomly for you
	 */
	public static function create_hash($password) {
		return \password_hash($password, PASSWORD_DEFAULT, self::options());
	}

	public static function validate_password($password, $good_hash) {
		if (\password_verify($password, $good_hash)) {
		    return true;
		} else {
			return oldPasswdTools::validate_password($password, $good_hash);
		}
		return false;
	}

	public static function isPasswordNeedsRehash($password,$hash) {
        if (\password_needs_rehash($hash, PASSWORD_DEFAULT, self::options())) {
            return self::create_hash($password);
        }
        return false;
	}
	
} 
