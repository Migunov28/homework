<?php
/**
 @ Cron Referral program by Munay, v1.2.0
 */

include_once('../merchan/cpayeer/cpayeer.php');

# class autoload
function __autoload($name) { include_once("../engine/class.".$name.".php"); }

$config   =   new config; // config class
$validate =   new validate; // validate class
$db   		= 	new db( $config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB, $config->EncodeDB ); // db connection
$db2 		  = 	new db( $config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB, $config->EncodeDB );	// create an instance of db class for the cycle
$db3 		  = 	new db( $config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB, $config->EncodeDB );
$db4      = 	new db( $config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB, $config->EncodeDB );
$db5      = 	new db( $config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB, $config->EncodeDB );
$db6      = 	new db( $config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB, $config->EncodeDB );

$accountNumber = $p_out_number;
$apiId = $p_out_id;
$apiKey = $p_out_key;


//====================================================================================Level 0 to 1
$lvl0 = $db->Query("SELECT uid,ref FROM t_users WHERE matrix1 = 0 AND bal >= '200' ORDER BY uid ASC");
while($row0 = $db->FetchAssoc()) {

	$ref = $row0['ref'];

	// We're checking how much the advertiser invited
	$db2->Query("SELECT uid FROM t_users WHERE ref='$ref' AND matrix1 > 0");
	$ref_how = $db2->NumRows();
	if ($ref_how >= 2) {
	$iend = 0;

	// If he has 2 referrals
	$wu_q = $db2->Query("SELECT uid FROM t_users WHERE ref='$ref' AND matrix1 > 0 ORDER BY uid ASC");
	while($row = $db2->FetchAssoc()) {

		$db3->Query("SELECT uid FROM t_users WHERE ref='$row[uid]' AND matrix1 > 0");
		$ref_line_1_how = $db3->NumRows();
		if ($ref_line_1_how < 2) { $iend = 1; $ref = $row['uid']; break; }

	}

	// Line 2
	if ($iend == 0) {

	$wu_q = $db2->Query("SELECT uid FROM t_users WHERE ref='$ref' AND matrix1 > 0 ORDER BY uid ASC");
	while($row = $db2->FetchAssoc()) {

			$wu_q_2 = $db3->Query("SELECT uid FROM t_users WHERE ref='$row[uid]' AND matrix1 > 0 ORDER BY uid ASC");
			while($row2 = $db3->FetchAssoc()) {

				$db4->Query("SELECT uid FROM t_users WHERE ref='$row2[uid]' AND matrix1 > 0");
				$ref_line_1_how = $db4->NumRows();
				if ($ref_line_1_how < 2) { $iend = 1; $ref = $row2['uid']; break 2; }

			}
		}
	}

	// Line 3
	if ($iend == 0) {

		$wu_q = $db2->Query("SELECT uid FROM t_users WHERE ref='$ref' AND matrix1 > 0 ORDER BY uid ASC");
		while($row = $db2->FetchAssoc()) {

			$wu_q_2 = $db3->Query("SELECT uid FROM t_users WHERE ref='$row[uid]' AND matrix1 > 0 ORDER BY uid ASC");
			while($row2 = $db3->FetchAssoc()) {

					$wu_q_3 = $db4->Query("SELECT uid FROM t_users WHERE ref='$row2[uid]' AND matrix1 > 0 ORDER BY uid ASC");
					while($row3 = $db4->FetchAssoc()) {

					$db5->Query("SELECT uid FROM t_users WHERE ref='$row3[uid]' AND matrix1 > 0");
					$ref_line_1_how = $db5->NumRows();
					if ($ref_line_1_how < 2) { $iend = 1; $ref = $row3['uid']; break 3; }

					}
			}
		}
	}

	// Line 4
	if ($iend == 0) {

		$wu_q = $db2->Query("SELECT uid FROM t_users WHERE ref='$ref' AND matrix1 > 0 ORDER BY uid ASC");
		while($row = $db2->FetchAssoc()) {

			$wu_q_2 = $db3->Query("SELECT uid FROM t_users WHERE ref='$row[uid]' AND matrix1 > 0 ORDER BY uid ASC");
			while($row2 = $db3->FetchAssoc()) {

					$wu_q_3 = $db4->Query("SELECT uid FROM t_users WHERE ref='$row2[uid]' AND matrix1 > 0 ORDER BY uid ASC");
					while($row3 = $db4->FetchAssoc()) {

						$wu_q_4 = $db5->Query("SELECT uid FROM t_users WHERE ref='$row3[uid]' AND matrix1 > 0 ORDER BY uid ASC");

						while($row4 = $db5->FetchAssoc()) {

						$db6->Query("SELECT uid FROM t_users WHERE ref='$row4[uid]' AND matrix1 > 0");
						$ref_line_1_how = $db6->NumRows();

						if ($ref_line_1_how < 2) { $iend = 1; $ref = $row4['uid']; break 4; }

						}
					}

			}
		}
	}

} // END if ($ref_how >= 2)

	$db2->Query( "SELECT uid,pay_payeer FROM t_users WHERE uid='$ref' LIMIT 1");
	$referer = $db2->FetchAssoc();

	$db2->Query("UPDATE `t_users` SET `ref` = '$ref', `bal` = `bal`-'100', `matrix1` = '1' WHERE uid = '$row0[uid]' LIMIT 1");
	$db2->Query("UPDATE `t_users` SET `bal` = `bal`+'75', `profit` = `profit`+'50' WHERE uid = '$ref' LIMIT 1");

	$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
	if ($payeer->isAuth()) {

			$arTransfer = $payeer->transfer(array(
			'curIn' => 'RUB',
			'sum' => '25.0',
			'curOut' => 'RUB',
			'to' => $referer['pay_payeer'],
			'comment' => iconv('windows-1251', 'utf-8', 'Profit by '.SITE)
			));

			if (empty($arTransfer['errors'])) {

				return true;

			} else {

				echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
				exit('6');

			}

	} else {

		echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
		exit('6');

		}


}




//====================================================================================Level 1 to 2
$lvl1 = $db->Query("SELECT uid,ref FROM t_users WHERE matrix1 = 1 AND bal >= '150' ORDER BY uid ASC");
while($row1 = $db->FetchAssoc()) {

	$ref = $row1['ref'];

	$db2->Query( "SELECT uid,ref FROM t_users WHERE uid='$ref' LIMIT 1");
	$f_ref_pre_2 = $db2->FetchAssoc();
	$ref_pay = $f_ref_pre_2['ref'];


	if (empty($ref_pay)) { $ref_pay = $admin_uid; }

	$db2->Query("SELECT uid,pay_payeer FROM t_users WHERE uid='$ref_pay' LIMIT 1");
	$referer = $db2->FetchAssoc();

	if (!empty($f_ref_pre_2['ref'])) {

		$db2->Query("UPDATE `t_users` SET `bal` = `bal`+'100', `profit` = `profit`+'100' WHERE uid = '$ref_pay' LIMIT 1");

		} else {

		$db2->Query("UPDATE `t_users` SET `profit` = `profit`+'100' WHERE uid = '$ref_pay' LIMIT 1");

	}


	$db2->Query("UPDATE `t_users` SET `bal` = `bal`-'150', `matrix1` = '2' WHERE uid = '$row1[uid]' LIMIT 1");

	$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
	if ($payeer->isAuth()) {

			$arTransfer = $payeer->transfer(array(
			'curIn' => 'RUB',
			'sum' => '50.0',
			'curOut' => 'RUB',
			'to' => $referer['pay_payeer'],
			'comment' => iconv('windows-1251', 'utf-8', 'Profit by '.SITE)
			));

			if (empty($arTransfer['errors'])) {

				return true;

			} else {

				echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
				exit('6');

			}

	} else {

		echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
		exit('6');

		}


}



//====================================================================================Level 2 to 3
$lvl2 = $db->Query("SELECT uid,ref FROM t_users WHERE matrix1 = 2 AND bal >= '400' ORDER BY uid ASC");
while($row2 = $db->FetchAssoc()) {

	$ref = $row2['ref'];

	$db2->Query("SELECT uid,ref FROM t_users WHERE uid='$ref' LIMIT 1");
	$f_ref_pre_2 = $db2->FetchAssoc();

	$db2->Query("SELECT uid,ref FROM t_users WHERE uid='$f_ref_pre_2[ref]' LIMIT 1");
	$f_ref_pre_3 = $db2->FetchAssoc();

	$ref_pay = $f_ref_pre_3['ref'];


	if (empty($ref_pay)) { $ref_pay = $admin_uid; }

	$db2->Query("SELECT uid,pay_payeer FROM t_users WHERE uid='$ref_pay' LIMIT 1");
	$referer = $db2->FetchAssoc();

	if (!empty($f_ref_pre_3['ref'])) {

		$db2->Query("UPDATE `t_users` SET `bal` = `bal`+'250', `profit` = `profit`+'300' WHERE uid = '$ref_pay' LIMIT 1");

		} else {

		$db2->Query("UPDATE `t_users` SET `profit` = `profit`+'300' WHERE uid = '$ref_pay' LIMIT 1");

	}

	$db2->Query("UPDATE `t_users` SET `bal` = `bal`-'400', `matrix1` = '3' WHERE uid = '$row2[uid]' LIMIT 1");

	$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
	if ($payeer->isAuth()) {

			$arTransfer = $payeer->transfer(array(
			'curIn' => 'RUB',
			'sum' => '150.0',
			'curOut' => 'RUB',
			'to' => $referer['pay_payeer'],
			'comment' => iconv('windows-1251', 'utf-8', 'Profit by '.SITE)
			));

			if (empty($arTransfer['errors'])) {

				return true;

			} else {

				echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
				exit('6');

			}

	} else {

		echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
		exit('6');

		}


}



//====================================================================================Level 3 to 4
$lvl3 = $db->Query("SELECT uid,ref FROM t_users WHERE matrix1 = 3 AND bal >= '2000' ORDER BY uid ASC");
while($row3 = $db->FetchAssoc()) {

	$ref = $row3['ref'];

	$db2->Query("SELECT uid,ref FROM t_users WHERE uid='$ref' LIMIT 1");
	$f_ref_pre_2 = $db2->FetchAssoc();

	$db2->Query("SELECT uid,ref FROM t_users WHERE uid='$f_ref_pre_2[ref]' LIMIT 1");
	$f_ref_pre_3 = $db2->FetchAssoc();

	$db2->Query("SELECT uid,ref FROM t_users WHERE uid='$f_ref_pre_3[ref]' LIMIT 1");
	$f_ref_pre_4 = $db2->FetchAssoc();
	$ref_pay = $f_ref_pre_4['ref'];


	if (empty($ref_pay)) { $ref_pay = $admin_uid; }

	$db2->Query("SELECT uid,pay_payeer FROM t_users WHERE uid='$ref_pay' LIMIT 1");
	$referer = $db2->FetchAssoc();

	if (!empty($f_ref_pre_4['ref'])) {

		$db2->Query("UPDATE `t_users` SET `bal` = `bal`+'312.5', `profit` = `profit`+'3987.5' WHERE uid = '$ref_pay' LIMIT 1");

		} else {

		$db2->Query("UPDATE `t_users` SET `profit` = `profit`+'3987.5' WHERE uid = '$ref_pay' LIMIT 1");

	}

	$db2->Query("UPDATE `t_users` SET `bal` = `bal`-'2000', `matrix1` = '4' WHERE uid = '$row3[uid]' LIMIT 1");

	$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
	if ($payeer->isAuth()) {

			$arTransfer = $payeer->transfer(array(
			'curIn' => 'RUB',
			'sum' => '1687.50',
			'curOut' => 'RUB',
			'to' => $referer['pay_payeer'],
			'comment' => iconv('windows-1251', 'utf-8', 'Profit by '.SITE)
			));

			if (empty($arTransfer['errors'])) {

				return true;

			} else {

				echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
				exit('6');

			}

	} else {

		echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
		exit('6');

		}

}


//====================================================================================Level 4 to 1
$lvl4 = $db->Query("SELECT uid,ref FROM t_users WHERE matrix1 = 4 AND bal >= '100' ORDER BY uid ASC");
while($row5 = $db->FetchAssoc()) {

	//Determining next in Line
	$noref = $db2->Query("SELECT uid AS `druid`, (SELECT COUNT(`uid`) FROM t_users WHERE ref = druid AND matrix1 > 0) as cnt FROM t_users WHERE matrix1 > 0 ORDER BY uid ASC");
	while($row_n = $db2->FetchAssoc()) {

		if ($row_n['cnt'] < 2) { $ref = $row_n['druid']; break; }

	}

	$db2->Query("SELECT uid,pay_payeer FROM t_users WHERE uid='$ref' LIMIT 1");
	$referer = $db2->FetchAssoc();

	$db2->Query("UPDATE `t_users` SET `ref` = '$ref', `bal` = `bal`-'100', `matrix1` = '1' WHERE uid = '$row5[uid]' LIMIT 1");
	$db2->Query("UPDATE `t_users` SET `bal` = `bal`+'75', `profit` = `profit`+'50' WHERE uid = '$ref' LIMIT 1");

	$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
	if ($payeer->isAuth()) {

			$arTransfer = $payeer->transfer(array(
			'curIn' => 'RUB',
			'sum' => '25.0',
			'curOut' => 'RUB',
			'to' => $referer['pay_payeer'],
			'comment' => iconv('windows-1251', 'utf-8', 'Profit by '.SITE)
			));

			if (empty($arTransfer['errors'])) {

				return true;

			} else {

				echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
				exit('6');

			}

	} else {

		echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
		exit('6');

		}


}
