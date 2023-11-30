<?php
/*


define( 'SIMPLE_STATS_PATH', realpath( dirname( __FILE__ ) ) );
include_once( SIMPLE_STATS_PATH.'/config.php' );
include_once( SIMPLE_STATS_PATH.'/includes/classes.php' );
include_once( SIMPLE_STATS_PATH.'/includes/ua.php' );
*/
class Utils {


    public static  $Aborted=1;
    public static  $Valid=0;
	
    public static function IsWebView($user_agent) {
		// if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.hedi.blue.app") {
			// echo 'Android';
		// }
        if (strpos($user_agent['HTTP_USER_AGENT'], 'com.hedi.blue.app') !== false){
			return  true;
		}
        return false;
    }
    public static function FormatNbre($nbre) {
        $f_dt = number_format($nbre, 0, ",", " ");
        return $f_dt;
    }

    public static function getAccessModeLabel($code) {
        return $code == '1' ? 'Public' : 'Privé';
    }

    public static function getCompteurTypeSpan($code) {
		$type='Non défini';
		if($code == '0'){
			$type='Monophasé';
		}else if($code == '1'){
			$type='Triphasé';			
		}
		 return '<span class="badge badge-primary">'. $type . '</span>';
    }

    public static function getResponseModeLabel($code) {
        return $code == '1' ? 'Une fois' : 'Plusieurs fois';
    }

    public static function getProjetIs_ActiveLabel($code) {
        return $code == 'on' ? 'Oui' : 'Non';
    }

    public static function getIs_ActiveLabel($code) {
        return $code == 'on' ? 'Oui' : 'Non';
    }
	
    public static function getIs_ActiveLabelDigit($code) {
        return $code == '1' ? 'Oui' : 'Non';
    }
    public static function getStatut_IDentification($code) {
        return $code == '1' ? 'Installé' : 'Non installé';
    }
	
    public static function getAssign_Control_Statut($code) {
		if($code == '0'){
			return "Non contrôlé";
		}else if($code == '1'){
			return "Contrôlé";
		}
        return 'Inconnu';
    }
    public static function getAssign_Ticket_Statut($code) {
		if($code == ''){
			return "Sans Ticket";
		}else {
			return "Avec Ticket";
		}
        return 'Inconnu';
    }
    public static function getAssign_Install_Statut($code) {
		if($code == '0'){
			return "Non éxécutée";
		}else if($code == '1'){
			return "Exécutée";
		}
        return 'Inconnu';
    }
	
    public static function getInstallType_Badge($code) {
		if($code == '1'){
			return "badge-danger";
		}else if($code == '2'){
			return "badge-info";
		}else if($code == '7'){//Pilote
			return "badge-light";
		}else {
			return "badge-primary";
		} 
    }
	
    public static function getBadgeDispatch($code) {
		if($code == '1'){
			return "badge-light";
		}else {
			return "badge-info";
		} 
    }
	
    public static function getApproved_Label($code) {
		if($code == '1'){
			return "Approuvé";
		}else {
			return "Non approuvé";
		} 
    }
	
    public static function getInstallType_Label($code) {
		if($code == '0'){
			return "Installation";
		}else if($code == '1'){
			return "Remplacement";
		}else if($code == '7'){
			return "Pilote";
		}
    }
	
    public static function getAssign_Ticket_Badge($code) {
		if($code != ''){
			return "badge-success";
		}else {
			return "badge-danger";
		} 
    }
	
    public static function getAssign_Control_Badge($code) {
		if($code == '1'){
			return "badge-success";
		}else {
			return "badge-danger";
		} 
    }
	
    public static function getInstallationEnplaceSPAN($cpteur_actuel,$cpteur_install) {
		$badge="";
		if($cpteur_actuel == $cpteur_install){
		
			$badge= '<span class="badge badge-success">En place</span>';
		}else { 
			$badge= '<span class="badge badge-danger">Déclassé</span>';
		} 
		return  $badge;
    }
	 
	
    public static function getDateInstall_Label($code) {
		if($code == '1'){
			return "Date Installation";
		}else {
			return "Date début travaux";
		} 
    }
    public static function getDateInstall_Value($code,$install,$en_cours) {
		if($code == '1'){
			return $install;
		}else {
			return $en_cours;
		} 
    }

    public static function getUserAgent($SERVER, $var_name) {
        if (isset($SERVER[$var_name])) {
            // echo $var_name.'='.$POST[$var_name].'<br>';
            return strip_tags($SERVER[$var_name]);
        }
        return '';
    }

    public static function getIPclient($SERVER, $var_name) {
        //$_SERVER['REMOTE_ADDR']
        if (isset($SERVER['REMOTE_ADDR'])) {
            // echo $var_name.'='.$POST[$var_name].'<br>';
            return strip_tags($SERVER['REMOTE_ADDR']);
        }
        return '';
    }

    public static function StripTAG($code) {
        return strip_tags($code);
    }

    public static function uniqUid($table, $key_fld, $connection) {
        $bytes = md5(mt_rand());
        if (self::VerifierExistance($key_fld, $bytes, $table, $connection)) {
            $bytes = self::uniqUid($table, $key_fld, $connection);
        }
        return $bytes;
    }
	public static function uniqUid2( ) {
        $bytes = "pdf/generated/" . md5(mt_rand()). ".png";
		
        if (file_exists($bytes)) {
            $bytes = self::uniqUid2();
        }
		 
        return $bytes;
    }

    public static function VerifierExistance($pKey, $NoGenerated, $table, $connection) {
        $retour = false;
        $sql = "select $pKey from $table where $pKey=:NoGenerated";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":NoGenerated", $NoGenerated);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $retour = true;
        } else {
            $retour = false;
        }
        return $retour;
    }

    public static function GetServerDateOrTime($p = "D") {
        $retour = "";
        if ($p == "Y") {
            $retour = date('Y');
        } else if ($p == "DT") {
            $retour = date('Y-m-d H:i:s');
        } else if ($p == "T") {
            $retour = date('H:i:s');
        } else if ($p == "D") {
            $retour = date('Y-m-d');
        }
        return $retour; //date('Y');//date('Y-m-d H:i:s');
    }

    public static function removeAllWhiteSpace($text) {
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', '', $text);
        $text = trim($text);
        return $text;
    }

    public static function paginate($total_rows, $page, $page_url,$records_per_page) {
        $range = 4; 
        $result = "<ul class=\"pagination\">";
        $href = $page <= 1 ? '#' : $page_url . 'page=1';
        $disabled = $page <= 1 ? 'disabled' : '';
        $result.= '<li class="page-item ' . $disabled . '" ><a href="' . $href . '" class="page-link"><i class="fa fa-angle-double-left"></i></a></li>';
        $total_pages = ceil($total_rows / $records_per_page);
        $prev = $page - 1;
        $next = $page + 1;
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;
        for ($x = $initial_num; $x < $condition_limit_num; $x++) {
            if (($x > 0) && ($x <= $total_pages)) {
                if ($x == $page) {
                    $result.= "<li class='page-item active'><a class='page-link' href=\"#\">$x</a></li>";
                } else {
                    $result.= "<li class='page-item' ><a class='page-link' href='{$page_url}page=$x'>$x</a></li>";
                }
            }
        }
        $href = $page == $total_pages ? '#' : $page_url . 'page=' . $total_pages;
        $disabled = $page == $total_pages ? 'disabled' : '';
        $result.= '<li class="page-item ' . $disabled . '" ><a href="' . $href . '" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>';
        $result.= "</ul>";
        return $result;
}

public static function removeWhiteSpace($text)
{
/*
$ro = preg_replace('/\s+/', ' ', $row['message']); 
echo $ro;
echo preg_replace('/\s{2,}/', ' ', "This is   a Text \n and so on \t     Text text."); // This is a Text and so on Text text.


preg_replace('/\\s+/', ' ',$data)

$data = 'This is   a Text 
and so on         Text text on multiple lines and with        whitespaces';
$data= preg_replace('/\\s+/', ' ',$data);
echo $data;

$vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
$onlyconsonants = str_replace($vowels, "", "Hello World of PHP");
*/
$text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
$text = preg_replace('/([\s])\1+/', ' ', $text);
$text = trim($text);
return $text;
}

public static function responseJson($data) {
$requestContentType = 'application/json';
$httpVersion = "HTTP/1.1";
$statusCode = 200;
$statusMessage = 'OK';
$response = json_encode($data);
header($httpVersion . " " . $statusCode . " " . $statusMessage);
header("Content-Type:" . $requestContentType);

echo $response;
}

public static function encodeJson($responseData) {
$jsonResponse = json_encode($responseData);
return $jsonResponse;
}

public static function ClientToDbDateFormat($c_date) {
	if(empty($c_date)) return null;
$n_date = str_ireplace('/', '-', $c_date);
$f_dt = date('Y-m-d', strtotime($n_date));
return $f_dt;
}

public static function ClientToDbDateTimeFormat($c_date) {
$n_date = str_ireplace('/', '-', $c_date);
$f_dt = date('Y-m-d H:i:s', strtotime($n_date));
return $f_dt;
}
public static function UpperCase($data) {
//return strtoupper($data);
return ($data);
}
public static function DbToClientDateTimeFormat($c_date) {
$n_date = str_ireplace('-', '/', $c_date);
$f_dt = date('d/m/Y H:i:s', strtotime($n_date));
return $f_dt;
}
public static function DbToClientDateFormat($c_date) {
$n_date = str_ireplace('-', '/', $c_date);
$f_dt = date('d/m/Y', strtotime($n_date));
return $f_dt;
}


public static function GetFirstDayOfCurrentMonth() { 
	return date('Y-m'.'-01');;
}

function hash( $str, $salt="123456" ) {
		return sha1( $str . $salt);
	}

	
static function utf8_encode( $_str ) {
		$encoding = mb_detect_encoding( $_str );
		if ( $encoding == false || strtoupper( $encoding ) == 'UTF-8' || strtoupper( $encoding ) == 'ASCII' )
			return $_str;

		return iconv( $encoding, 'UTF-8', $_str );
}

function format_number( $_number, $_dp = 1 ) {
	$decimal = __( '.', 'decimal_point' );
	$thousands = __( ' ', 'thousands_separator' );
	$str = number_format( $_number, $_dp, $decimal, $thousands );
	if ( $str == '0'.$decimal.'0' && $_dp == 1 ) {
		$str2 = number_format( $_number, 2, $decimal, $thousands );
		if ( $str2 != '0'.$decimal.'00' ) {
			return $str2;
		}
	}
	return $str;
}
public static function GetLastDayOfCurrentMonth() { 
	$lastday = date('t',strtotime(date('Y-m'.'-01'))); 
	$last_day_this_month = date('Y-m'.'-'.$lastday);
	return $last_day_this_month;
}

public static function sp2nb( $_str ) {
	return str_replace( ' ', '&nbsp;', $_str );
}

/**
	 * Try to work out the original client IP address.
	 * If all we end up with is a private IP, discard it.
	 */
	private function determine_remote_ip() {
		// headers to look for, in order of priority
		$headers_to_check = array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED_HOST', 'REMOTE_ADDR' );

		foreach( $headers_to_check as $header ) {
			if( empty( $_SERVER[$header] ) )
				continue;

			$ips = explode( ',', $_SERVER[$header] );
			foreach( $ips as $ip ) {
				$ip = trim( $ip );
				if( $ip && ! preg_match( '/^(10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|192\.168\.)/i', $ip ) )	// private network IPs
					return $ip;
			}
		}
		
		return '';
	}
	
	/**
	 * Try to work out the requested resource.
	 */
	private function determine_resource() {
		if( isset( $_SERVER['REQUEST_URI'] ) )
			return $_SERVER['REQUEST_URI'];
		elseif( isset( $_SERVER['SCRIPT_NAME'] ) )
			return $_SERVER['SCRIPT_NAME'] . ( empty( $_SERVER['QUERY_STRING'] ) ? '' : '?' . $_SERVER['QUERY_STRING'] );
		elseif( isset( $_SERVER['PHP_SELF'] ) )
			return $_SERVER['PHP_SELF'] . ( empty( $_SERVER['QUERY_STRING'] ) ? '' : '?' . $_SERVER['QUERY_STRING'] );
		return '';
	}


	private function determine_language() {
		// Capture up to the first delimiter (comma found in Safari)
		if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) && preg_match( "/([^,;]*)/", $_SERVER['HTTP_ACCEPT_LANGUAGE'], $langs ) )
			return strtolower( $langs[0] );
		return '';
	}
	
	
	
public static function country_name( $code ) {
	$countries = array(
	'AD' => 'Andorra',
	'AE' => 'United Arab Emirates',
	'AF' => 'Afghanistan',
	'AG' => 'Antigua and Barbuda',
	'AI' => 'Anguilla',
	'AL' => 'Albania',
	'AM' => 'Armenia',
	'AN' => 'Netherlands Antilles',
	'AO' => 'Angola',
	'AQ' => 'Antarctica',
	'AR' => 'Argentina',
	'AS' => 'American Samoa',
	'AT' => 'Austria',
	'AU' => 'Australia',
	'AW' => 'Aruba',
	'AX' => 'Åland Islands',
	'AZ' => 'Azerbaijan',
	'BA' => 'Bosnia and Herzegovina',
	'BB' => 'Barbados',
	'BD' => 'Bangladesh',
	'BE' => 'Belgium',
	'BF' => 'Burkina Faso',
	'BG' => 'Bulgaria',
	'BH' => 'Bahrain',
	'BI' => 'Burundi',
	'BJ' => 'Benin',
	'BL' => 'Saint Barthélemy',
	'BM' => 'Bermuda',
	'BN' => 'Brunei Darussalam',
	'BO' => 'Bolivia Plurinational State of',
	'BR' => 'Brazil',
	'BS' => 'Bahamas',
	'BT' => 'Bhutan',
	'BV' => 'Bouvet Island',
	'BW' => 'Botswana',
	'BY' => 'Belarus',
	'BZ' => 'Belize',
	'CA' => 'Canada',
	'CC' => 'Cocos (Keeling) Islands',
	'CD' => 'Congo The Democratic Republic of the',
	'CF' => 'Central African Republic',
	'CG' => 'Congo',
	'CH' => 'Switzerland',
	'CI' => 'Côte d\'Ivoire',
	'CK' => 'Cook Islands',
	'CL' => 'Chile',
	'CM' => 'Cameroon',
	'CN' => 'China',
	'CO' => 'Colombia',
	'CR' => 'Costa Rica',
	'CU' => 'Cuba',
	'CV' => 'Cape Verde',
	'CX' => 'Christmas Island',
	'CY' => 'Cyprus',
	'CZ' => 'Czech Republic',
	'DE' => 'Germany',
	'DJ' => 'Djibouti',
	'DK' => 'Denmark',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'DZ' => 'Algeria',
	'EC' => 'Ecuador',
	'EE' => 'Estonia',
	'EG' => 'Egypt',
	'EH' => 'Western Sahara',
	'ER' => 'Eritrea',
	'ES' => 'Spain',
	'ET' => 'Ethiopia',
	'FI' => 'Finland',
	'FJ' => 'Fiji',
	'FK' => 'Falkland Islands (Malvinas)',
	'FM' => 'Micronesia Federated States of',
	'FO' => 'Faroe Islands',
	'FR' => 'France',
	'GA' => 'Gabon',
	'GB' => 'United Kingdom',
	'GD' => 'Grenada',
	'GE' => 'Georgia',
	'GF' => 'French Guiana',
	'GG' => 'Guernsey',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GL' => 'Greenland',
	'GM' => 'Gambia',
	'GN' => 'Guinea',
	'GP' => 'Guadeloupe',
	'GQ' => 'Equatorial Guinea',
	'GR' => 'Greece',
	'GS' => 'South Georgia and the South Sandwich Islands',
	'GT' => 'Guatemala',
	'GU' => 'Guam',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HK' => 'Hong Kong',
	'HM' => 'Heard Island and McDonald Islands',
	'HN' => 'Honduras',
	'HR' => 'Croatia',
	'HT' => 'Haiti',
	'HU' => 'Hungary',
	'ID' => 'Indonesia',
	'IE' => 'Ireland',
	'IL' => 'Israel',
	'IM' => 'Isle of Man',
	'IN' => 'India',
	'IO' => 'British Indian Ocean Territory',
	'IQ' => 'Iraq',
	'IR' => 'Iran Islamic Republic of',
	'IS' => 'Iceland',
	'IT' => 'Italy',
	'JE' => 'Jersey',
	'JM' => 'Jamaica',
	'JO' => 'Jordan',
	'JP' => 'Japan',
	'KE' => 'Kenya',
	'KG' => 'Kyrgyzstan',
	'KH' => 'Cambodia',
	'KI' => 'Kiribati',
	'KM' => 'Comoros',
	'KN' => 'Saint Kitts and Nevis',
	'KP' => 'Korea Democratic People\'s Republic of',
	'KR' => 'Korea Republic of',
	'KW' => 'Kuwait',
	'KY' => 'Cayman Islands',
	'KZ' => 'Kazakhstan',
	'LA' => 'Lao People\'s Democratic Republic',
	'LB' => 'Lebanon',
	'LC' => 'Saint Lucia',
	'LI' => 'Liechtenstein',
	'LK' => 'Sri Lanka',
	'LR' => 'Liberia',
	'LS' => 'Lesotho',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'LV' => 'Latvia',
	'LY' => 'Libyan Arab Jamahiriya',
	'MA' => 'Morocco',
	'MC' => 'Monaco',
	'MD' => 'Republic of Moldova',
	'ME' => 'Montenegro',
	'MF' => 'Saint Martin',
	'MG' => 'Madagascar',
	'MH' => 'Marshall Islands',
	'MK' => 'Macedonia The Former Yugoslav Republic of',
	'ML' => 'Mali',
	'MM' => 'Myanmar',
	'MN' => 'Mongolia',
	'MO' => 'Macao',
	'MP' => 'Northern Mariana Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MS' => 'Montserrat',
	'MT' => 'Malta',
	'MU' => 'Mauritius',
	'MV' => 'Maldives',
	'MW' => 'Malawi',
	'MX' => 'Mexico',
	'MY' => 'Malaysia',
	'MZ' => 'Mozambique',
	'NA' => 'Namibia',
	'NC' => 'New Caledonia',
	'NE' => 'Niger',
	'NF' => 'Norfolk Island',
	'NG' => 'Nigeria',
	'NI' => 'Nicaragua',
	'NL' => 'Netherlands',
	'NO' => 'Norway',
	'NP' => 'Nepal',
	'NR' => 'Nauru',
	'NU' => 'Niue',
	'NZ' => 'New Zealand',
	'OM' => 'Oman',
	'PA' => 'Panama',
	'PE' => 'Peru',
	'PF' => 'French Polynesia',
	'PG' => 'Papua New Guinea',
	'PH' => 'Philippines',
	'PK' => 'Pakistan',
	'PL' => 'Poland',
	'PM' => 'Saint Pierre and Miquelon',
	'PN' => 'Pitcairn',
	'PR' => 'Puerto Rico',
	'PS' => 'Palestinian Territory Occupied',
	'PT' => 'Portugal',
	'PW' => 'Palau',
	'PY' => 'Paraguay',
	'QA' => 'Qatar',
	'RE' => 'Réunion',
	'RO' => 'Romania',
	'RS' => 'Serbia',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'SA' => 'Saudi Arabia',
	'SB' => 'Solomon Islands',
	'SC' => 'Seychelles',
	'SD' => 'Sudan',
	'SE' => 'Sweden',
	'SG' => 'Singapore',
	'SH' => 'Saint Helena',
	'SI' => 'Slovenia',
	'SJ' => 'Svalbard and Jan Mayen',
	'SK' => 'Slovakia',
	'SL' => 'Sierra Leone',
	'SM' => 'San Marino',
	'SN' => 'Senegal',
	'SO' => 'Somalia',
	'SR' => 'Suriname',
	'ST' => 'Sao Tome and Principe',
	'SV' => 'El Salvador',
	'SY' => 'Syrian Arab Republic',
	'SZ' => 'Swaziland',
	'TC' => 'Turks and Caicos Islands',
	'TD' => 'Chad',
	'TF' => 'French Southern Territories',
	'TG' => 'Togo',
	'TH' => 'Thailand',
	'TJ' => 'Tajikistan',
	'TK' => 'Tokelau',
	'TL' => 'Timor-Leste',
	'TM' => 'Turkmenistan',
	'TN' => 'Tunisia',
	'TO' => 'Tonga',
	'TR' => 'Turkey',
	'TT' => 'Trinidad and Tobago',
	'TV' => 'Tuvalu',
	'TW' => 'Taiwan Province of China',
	'TZ' => 'Tanzania United Republic of',
	'UA' => 'Ukraine',
	'UG' => 'Uganda',
	'UM' => 'United States Minor Outlying Islands',
	'US' => 'United States',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VA' => 'Holy See (Vatican City State)',
	'VC' => 'Saint Vincent and the Grenadines',
	'VE' => 'Venezuela Bolivarian Republic of',
	'VG' => 'Virgin Islands British',
	'VI' => 'Virgin Islands U.S.',
	'VN' => 'Viet Nam',
	'VU' => 'Vanuatu',
	'WF' => 'Wallis and Futuna',
	'WS' => 'Samoa',
	'YE' => 'Yemen',
	'YT' => 'Mayotte',
	'ZA' => 'South Africa',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe'
	);
	
	return( isset( $countries[$code] ) ? $countries[$code] : $code );
}

public static function F_Exist($folder)
{
    // Get canonicalized absolute pathname
    $path = realpath($folder);

    // If it exist, check if it's a directory
    if($path !== false AND is_dir($path))
    {
        // Return canonicalized absolute pathname
      //  return $path;
    }else{
		mkdir($folder);		
	}

    // Path/folder does not exist
  //  return false;
}



public static function compress2($source, $destination, $quality) {
		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
}
	
	
	// created compressed JPEG file from source file
public static function compressImage($source_image, $compress_image) {
		
		/*
		
		
  // Getting file name
  $filename = $_FILES['imagefile']['name'];
 
  // Valid extension
  $valid_ext = array('png','jpeg','jpg');

  // Location
  $location = "images/".$filename;

  // file extension
  $file_extension = pathinfo($location, PATHINFO_EXTENSION);
  $file_extension = strtolower($file_extension);

  // Check extension
  if(in_array($file_extension,$valid_ext)){

    // Compress Image
    compressImage($_FILES['imagefile']['tmp_name'],$location,60);

  }else{
    echo "Invalid file type.";
  }
		
		*/
		
		
		$image_info = getimagesize($source_image);	
		if ($image_info['mime'] == 'image/jpeg') { 
			$source_image = imagecreatefromjpeg($source_image);
			imagejpeg($source_image, $compress_image, 75);
		} elseif ($image_info['mime'] == 'image/gif') {
			$source_image = imagecreatefromgif($source_image);
			imagegif($source_image, $compress_image, 75);
		} elseif ($image_info['mime'] == 'image/png') {
			$source_image = imagecreatefrompng($source_image);
			imagepng($source_image, $compress_image, 6);
		}	    
		return $compress_image;
	}
}
/*
$file_type_error = '';
	if($_FILES['upload_images']['name']) {	  
		$upload_dir = "uploads/";	
		if (($_FILES["upload_images"]["type"] == "image/gif") ||
		   ($_FILES["upload_images"]["type"] == "image/jpeg") ||
		   ($_FILES["upload_images"]["type"] == "image/png") ||
		   ($_FILES["upload_images"]["type"] == "image/pjpeg")) {
			$file_name = $_FILES["upload_images"]["name"];
			$extension = end((explode(".", $file_name)));
			$upload_file = $upload_dir.$file_name;		
			if(move_uploaded_file($_FILES['upload_images']['tmp_name'],$upload_file)){			  
				 $source_image = $upload_file;
				 $image_destination = $upload_dir."min-".$file_name;
				 $compress_images = compressImage($source_image, $image_destination);			 
			}		 
		} else {
			$file_type_error = "Upload only jpg or gif or png file type";
		}	
	}
function filter_link( $_filters, $text ) {
	global $is_archive;

	if( isset( $_filters['referrer'] ) || isset( $_filters['resource'] ) )
		$text = urldecode( $text );
	
	$text = htmlspecialchars( $text );

	// avoid super-long referrer strings
	if( strlen( $text ) > 100 )
		$text = substr( $text, 0, 100 ) . '&hellip;';
	
	// cannot filter archives
	if( $is_archive )
		return $text;
	
	$url = filter_url( $_filters );
	return "<a href='./$url' class='filter'>$text</a>";
}


:::::::::::OPTIONS
function setup_options() {
		$defaults = array(
			'stats_enabled' => true,
			'site_name' => '',
			'login_required' => false,
			'username' => '',
			'password' => '',
			'tz' => date_default_timezone_get(),
			'lang' => 'en-gb',
			'log_user_agents' => false,
			'log_bots' => false,
			'ignored_ips' => array(),
			'aggregate_after' => 0,
			'last_aggregated' => array( 'yr' => 0, 'mo' => 0 ),
			'salt' => sha1( rand() . date('Ymj') . 'simple-stats' . $_SERVER['SERVER_NAME'] ),
			'db_version' => self::db_version
		);
		
		$options = $this->load_options();
		
		foreach( $defaults as $k => $v ) {
			if( !isset( $options[$k] ) ) {
				$options[$k] = $v;
				$this->add_option( $k, $v );
			}
		}
		
		$this->update_option( 'db_version', self::db_version );
		
		$this->options = $this->load_options();	// reload
	}
	
	function add_option( $option, $value ) {
		$value = $this->esc( serialize( $value ) );
		$this->query( "INSERT INTO `{$this->tables['options']}` ( `option`, `value` ) VALUES ( '$option', '$value' )" );
	}
	
	function update_option( $option, $value ) {
		$value = $this->esc( serialize( $value ) );
		$rows = $this->query( "UPDATE `{$this->tables['options']}` SET `value` = '$value' WHERE `option` = '$option'" );
	}

private function load_options(){
		$options = array();
		$result = $this->query( "SELECT * FROM `{$this->tables['options']}`" );
		while( $row = @mysqli_fetch_assoc( $result ) ) {
			$options[$row['option']] = unserialize( $row['value'] );
		}
		
		$this->installed = isset( $options['stats_enabled'] );	// first run?
		
		return $options;
	}
	
		$pw = trim( $_POST['password'] );
		if( $pw )	// password has been set/changed
			$options['password'] = $ss->hash( $pw );
			
			
		$ips = explode( "\n", str_replace( "\r\n", "\n", $_POST['ignored_ips'] ) );
		$options['ignored_ips'] = array();
		// we don't check the validity of IPs
		foreach( $ips as $ip ) {
			$ip = trim( $ip );
			if( $ip )
				$options['ignored_ips'][] = $ip;
		}
		
		foreach( $options as $option => $value )
			$ss->update_option( $option, $value );
			
			
			@setcookie( 'simple_stats', $ss->hash( $ss->options['username'] . $ss->options['password'] ), time() + 31536000, '/', '' );
			header( 'Location: ./' . ( $origin ? './?p=' .  $origin : '' ), true, 302 );
			
			
	////////////
	
		if ( !$ss->is_installed() || !$ss->options['stats_enabled'] || 
			( isset( $_COOKIE['simple_stats'] ) && $_COOKIE['simple_stats'] == $ss->hash( $ss->options['username'] . $ss->options['password'] ) ) )
			return;
		
		$data = array();
		$data['remote_ip'] = substr( $this->determine_remote_ip(), 0, 39 );
		// check whether to ignore this hit
		if( in_array( $data['remote_ip'], $ss->options['ignored_ips'] ) )
			return;

		$data['resource'] = substr( $ss->utf8_encode( $this->determine_resource() ), 0, 255 );
		
		$ua = new SimpleStatsUA();
		$browser = $ua->parse_user_agent( $_SERVER['HTTP_USER_AGENT'] );
		$data['platform'] = $browser['platform'];
		$data['browser']  = $browser['browser'];
		$data['version']  = substr( $this->parse_version( $browser['version'] ), 0, 15 );
		
		// check whether to ignore this hit
		if ( $data['browser'] == 1 && $ss->options['log_bots'] == false )
			return;
		
		
	///////////////
:::::::::::OPTIONS

*/
?>