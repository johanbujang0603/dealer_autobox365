<?php

function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
    return '#' . random_color_part() . random_color_part() . random_color_part();
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}


function since($date_string)
{
    $time = time() - strtotime($date_string); // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'Year',
        2592000 => 'Month',
        604800 => 'Week',
        86400 => 'Day',
        3600 => 'Hour',
        60 => 'Minute',
        1 => 'Second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}


function recorgnizeCountriesFromPlateNumber($original_plateNumber)
{

    $plateNumber = preg_replace('/[a-zA-Z]/', 'L', $original_plateNumber);

    $plateNumber = preg_replace('/[0-9]/', 'D', $plateNumber);

    $patterns = array(
        ['country' => 'SO', 'pattern' => 'DDDD'],
        ['country' => 'SO', 'pattern' => 'DDDDD'],
        ['country' => 'TN', 'pattern' => 'DDDDDD'],
        ['country' => 'LY', 'pattern' => 'DDDDDDD'],
        ['country' => 'LY', 'pattern' => 'DDDDDDDD'],
        ['country' => 'DZ', 'pattern' => 'DDDDDDDDDD'],
        ['country' => 'ZW', 'pattern' => 'DDDDDDL'],
        ['country' => 'MA', 'pattern' => 'DDDDDLDD'],
        ['country' => 'KM', 'pattern' => 'DDDDL'],
        ['country' => 'RW', 'pattern' => 'DDDDL'],
        ['country' => 'KM', 'pattern' => 'DDDLLDD'],
        ['country' => 'GA', 'pattern' => 'DDDDLDL'],
        ['country' => 'RM', 'pattern' => 'DDDDLL'],
        ['country' => 'TG', 'pattern' => 'DDDDLL'],
        ['country' => 'EG', 'pattern' => 'DDDDLL'],
        ['country' => 'SD', 'pattern' => 'DDDDLL'],
        ['country' => 'GW', 'pattern' => 'DDDDLL'],
        ['country' => 'CI', 'pattern' => 'DDDDLLDD'],
        // ['country' => 'MR', 'pattern' => 'DDDDLLDD'],
        ['country' => 'MU', 'pattern' => 'DDDDLLDD'],
        ['country' => 'EG', 'pattern' => 'DDDDLLL'],
        ['country' => 'RM', 'pattern' => 'DDDDLL(L)'],
        ['country' => 'DJ', 'pattern' => 'DDDLD'],
        ['country' => 'DJ', 'pattern' => 'DDDLDD'],
        ['country' => 'CG', 'pattern' => 'DDDLLDD'],
        ['country' => 'CG', 'pattern' => 'DDDLLD'],
        ['country' => 'EG', 'pattern' => 'DDDLLL'],
        ['country' => 'DJ', 'pattern' => 'DDLDD'],
        ['country' => 'BF', 'pattern' => 'DDLLDDDD'],
        ['country' => 'TD', 'pattern' => 'DDLDDDDL'],
        ['country' => 'BI', 'pattern' => 'LDDDDL'],
        ['country' => 'NE', 'pattern' => 'DLDDDD'],
        ['country' => 'SD', 'pattern' => 'DLDDDDD'],
        ['country' => 'ET', 'pattern' => 'DLLDDDDD'],
        ['country' => 'ET', 'pattern' => 'DDDDDDLL'],
        ['country' => 'ML', 'pattern' => 'DLLLDDDD'],
        ['country' => 'SC', 'pattern' => 'LDDD'],
        // ['country' => 'MR', 'pattern' => 'LDDD'],
        ['country' => 'BJ', 'pattern' => 'LDDDD'],
        ['country' => 'KE', 'pattern' => 'LDDDD'],
        ['country' => 'LS', 'pattern' => 'LDDDD'],
        ['country' => 'LR', 'pattern' => 'LDDDD'],
        ['country' => 'SC', 'pattern' => 'LDDDD '],
        ['country' => 'SL', 'pattern' => 'LDDDD '],
        ['country' => 'SC', 'pattern' => 'LDDDDD'],
        ['country' => 'MA', 'pattern' => 'LDDDDDDD'],
        ['country' => 'NA', 'pattern' => 'LDDDDDLL'],
        ['country' => 'ML', 'pattern' => 'LDDDDLL'],
        // ['country' => 'CI', 'pattern' => 'LDDDDLLD'],
        ['country' => 'NE', 'pattern' => 'LDDDDLLD'],
        ['country' => 'TZ', 'pattern' => 'LDDDLL'],
        ['country' => 'BW', 'pattern' => 'LDDDLLL'],
        ['country' => 'GM', 'pattern' => 'LDLDDDD'],
        ['country' => 'MU', 'pattern' => 'LLDD'],
        ['country' => 'BJ', 'pattern' => 'LLDDDD'],
        ['country' => 'LS', 'pattern' => 'LLDDDD'],
        ['country' => 'LR', 'pattern' => 'LLDDDD'],
        ['country' => 'MW', 'pattern' => 'LLDDDD'],
        ['country' => 'GH', 'pattern' => 'LLDDDD'],
        ['country' => 'NA', 'pattern' => 'LLDDDD'],
        ['country' => 'RW', 'pattern' => 'LLDDDD'],
        ['country' => 'SL', 'pattern' => 'LLDDDD'],
        ['country' => 'ET', 'pattern' => 'LLDDDDD'],
        ['country' => 'ER', 'pattern' => 'LLDDDDDD'],
        ['country' => 'GH', 'pattern' => 'LLDDDDDD'],
        ['country' => 'TD', 'pattern' => 'LLDDDDL'],
        ['country' => 'GN', 'pattern' => 'LLDDDDL'],
        ['country' => 'SN', 'pattern' => 'LLDDDDLL'],
        // ['country' => 'CM', 'pattern' => 'LLDDDDL'],
        ['country' => 'GH', 'pattern' => 'LLDDDDL'],
        ['country' => 'TG', 'pattern' => 'LLDDDDL'],
        ['country' => 'CGO', 'pattern' => 'LLDDDDLL'],
        ['country' => 'GQ', 'pattern' => 'LLDDDL'],
        ['country' => 'CM', 'pattern' => 'LLDDDLL'],
        // ['country' => 'GA', 'pattern' => 'LLDDDLL'],
        ['country' => 'CF', 'pattern' => 'LLDDDLL'],
        ['country' => 'NG', 'pattern' => 'LLDDDLLL'],
        ['country' => 'CV', 'pattern' => 'LLDDLL'],
        ['country' => 'KE', 'pattern' => 'LLLDDD'],
        ['country' => 'UG', 'pattern' => 'LLLDDD'],
        ['country' => 'ZM', 'pattern' => 'LLLDDD'],
        ['country' => 'SL', 'pattern' => 'LLLDDD LL/LL'],
        ['country' => 'CV', 'pattern' => 'LLLDDDD'],
        ['country' => 'ST', 'pattern' => 'LLLDDDD'],
        ['country' => 'TZ', 'pattern' => 'LLLDDDD'],
        ['country' => 'ZM', 'pattern' => 'LLLDDDD'],
        ['country' => 'MZ', 'pattern' => 'LLLDDDD'],
        ['country' => 'ZW', 'pattern' => 'LLLDDDD'],
        ['country' => 'CF', 'pattern' => 'LLLDDDDL'],
        ['country' => 'GM', 'pattern' => 'LLLDDDDL'],
        ['country' => 'ST', 'pattern' => 'LLLDDDDL'],
        ['country' => 'KE', 'pattern' => 'LLLDDDL'],
        ['country' => 'UG', 'pattern' => 'LLLDDDL'],
        ['country' => 'RW', 'pattern' => 'LLLDDDL'],
        ['country' => 'SZ', 'pattern' => 'LLLDDDLL'],
        ['country' => 'MZ', 'pattern' => 'LLLDDDLL'],
        ['country' => 'NG', 'pattern' => 'LLLDDDLL'],
        ['country' => 'ZA', 'pattern' => 'LLLDDDLL'],
        ['country' => 'AO', 'pattern' => 'LLDDDDLL'],
    );
    $result = array();
    $extra_info = array();
    foreach ($patterns as $pattern) {
        if ($pattern['pattern'] == $plateNumber) {
            if ($pattern['country'] == 'MU') {
                if (checkForMauritius($original_plateNumber)) {
                    $result[] =  $pattern['country'];
                }
            }
            else if ($pattern['country'] == 'CM') {
                $extra = checkForCameroon($original_plateNumber);
                if ($extra) {
                    $result[] =  $pattern['country'];
                    $extra_info[] = $extra;
                }   
            }
            else if ($pattern['country'] == 'GA') {
                $extra = checkForGabon($original_plateNumber);
                if ($extra) {
                    $result[] =  $pattern['country'];
                    $extra_info[] = $extra;
                }   
            }
            else if ($pattern['country'] == 'CI') {
                $extra = checkForCotede($original_plateNumber);
                if ($extra) {
                    $result[] =  $pattern['country'];
                    $extra_info[] = $extra;
                }   
            }
            else if ($pattern['country'] == 'AO') {
                if (checkForAngola($original_plateNumber)) {
                    $result[] =  $pattern['country'];
                }
            }
            else if ($pattern['country'] == 'MR') {
                $extra = checkForMauritania($original_plateNumber);
                if ($extra) {
                    $extra_info[] = $extra;
                    $result[] =  $pattern['country'];
                }
            }
            else if ($pattern['country'] == 'CGO') {
                if (checkForDRCongo($original_plateNumber)) {
                    $result[] =  $pattern['country'];
                }
            }
            else if ($pattern['country'] == 'SN') {
                $extra = checkForSenegal($original_plateNumber);
                if ($extra) {
                    $result[] =  $pattern['country'];
                    $extra_info[] = $extra;
                }
            }
            else {
                $result[] =  $pattern['country'];
            }
        }
    }
    return array('result' => $result, 'extra' => $extra_info);
}

function checkForCameroon($plate_number) {
    $array = array('AD', 'CE', 'EN', 'ES', 'LT', 'NO', 'NW', 'OU', 'SU', 'SW');
    $regions = array(
        'AD' => 'Adamawa',
        'CE' => 'Center',
        'EN' => 'Far North',
        'ES' => 'East',
        'LT' => 'Litoral',
        'NO' => 'North',
        'NW' => 'North West',
        'OU' => 'West',
        'SU' => 'South',
        'SW' => 'South West'
    );
    $first2letters = strtoupper(substr($plate_number, 0, 2));
    if (in_array($first2letters, $array)) {
        return 'The car is registered in ' . $regions[$first2letters] . "(Cameroon)";
    }
    return false;
}

function checkForGabon($plate_number) {
    $array = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
    $regions = array(
        'G1' => 'Estuaire',
        'G2' => 'Upper Ogooue',
        'G3' => 'Average Ogooue',
        'G4' => 'Gouna',
        'G5' => 'Nyanha',
        'G6' => 'Ogooue-Ivindo',
        'G7' => 'Ogooue-Lolo',
        'G8' => 'Seaside Ogove',
        'G9' => 'Will-Ntem'
    );
    $first_letter = strtoupper(substr($plate_number, 4, 1));
    $fift_digital = strtoupper(substr($plate_number, 5, 1));
    if ($first_letter == 'G' && in_array($fift_digital, $array)) {
        $index = $first_letter . $fift_digital;
        return 'The car is registered in ' . $regions[$index] . " (Gabon)";
    }
    return false;
}

function checkForCotede($plate_number) {
    $array = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10');
    $regions = array(
        '01' =>  'Sud - Lagunes, Sud-Comoé, Agnéby',
        '02' =>  'Centre Ouest - Haut-Sassandra, Fromager, Marahoué',
        '03' =>  'Nord Savanes',
        '04' =>  'Centre Nord - Vallée du Bandama',
        '05' =>  'Est - Moyen-Comoé',
        '06' =>  'Ouest - Dix-Huit Montagnes',
        '07' =>  'Centre - Lacs, N’zi-Comoé',
        '08' =>  'Nord Est - Zanzan',
        '09' =>  'Sud Ouest - Bas-Sassandra',
        '10' =>  'Nord Ouest - Denguélé, Worodougou');
    $last2numbers = strtoupper(substr($plate_number, -2));
    if (in_array($last2numbers, $array)) {
        return 'The car is registered in ' . $regions[$last2numbers] . "(Cote D'Ivoire)";
    }
    return false;
}

function checkForAngola($plate_number) {
    $first2letters = strtoupper(substr($plate_number, 0, 2));
    if ($first2letters == 'LD')
        return true;
    return false;
}

function checkForSenegal($plate_number) {
    $array = array('DK', 'DL', 'FK', 'KL', 'KD', 'LG', 'SL', 'TC', 'TH', 'ZG', 'MT');
    $region = array(
        'DK' => 'Région de Dakar',
        'DL' => 'Région de Diourbel',
        'FK' => 'Région de Fatick',
        'KL' => 'Région de Kaolack',
        'KD' => 'Région de Kolda',
        'LG' => 'Région de Louga',
        'SL' => 'Région de Saint -Louis',
        'TC' => 'Région de Tambacounda',
        'TH' => 'Région de Thiès',
        'ZG' => 'Région de Ziguinchor',
        'MT' => 'Région de Matam',
    );
    $number = substr(strtoupper($plate_number), 0, 2);
    if (in_array($number, $array)) return 'The car is registered in ' . $region[$number] . '(Senegal)';
    else return false;
}

function checkForDRCongo($plate_number) {
    $array = array('BN', 'BC', 'BZ', 'EQ', 'KW', 'KE', 'KT', 'SH', 'KN', 'MN', 'NK', 'OR', 'HZ', 'SK', 'KV');
    $number = substr(strtoupper($plate_number), 0, 2);
    if (in_array($number, $array)) return true;
    else return false;
}

function checkForMauritius($plate_number) {
    $months = array('JN', 'FB', 'MR', 'AP', 'MY', 'JU', 'JL', 'AG', 'SE', 'OC', 'NV', 'DE');
    $array = array(
        'JN' => 'January',
        'FB' => 'February',
        'MR' => 'March',
        'AP' => 'April',
        'MY' => 'May',
        'JU' => 'June',
        'JL' => 'July',
        'AG' => 'August',
        'SE' => 'September',
        'OC' => 'October',
        'NV' => 'November',
        'DE' => 'December');
    $number = substr($plate_number, -4);
    $month = strtoupper(substr($number, 0, 2));
    if (in_array($month, $months)) {
        $year = '19';
        if ((int)$number > 20)
            $year = '20';
        $extra_info ='The car is registered in ' . $array[$month] . $year . $number;
        return $extra_info;
    }
    else return false;
}

function checkForMauritania($plate_number) {
    $array = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $region = array(
        '00' => 'Nouakchott',
        '01' => 'Al-Sharq Al Hudd',
        '02' => 'Al Hudd Al Gharbi',
        '03' => 'Al Aasaba',
        '04' => 'Kulak',
        '05' => 'Al Brakna',
        '06' => 'Al Trarza',
        '07' => 'Adrar',
        '08' => 'Dakhla Nuazibu',
        '09' => 'Takant',
        '10' => 'Hidimaha',
        '11' => 'Tiris Zimur',
        '12' => 'Inshyri'
    );
    $number = substr($plate_number, -2);
    if (in_array($number, $array)) {
        return 'The car is registered in ' . $region[$number] . '(Mauritania)';
    }
    else return false;
}

function formatPhoneNumber($number) {

    if(  preg_match( '/(^\+\d)(\d{3})(\d{3})(\d{4})$/', $number,  $matches ) )
    {
        $result = $matches[1] . ' ' .$matches[2] . ' ' . $matches[3] . ' ' . $matches[4];
        return $result;
    }
}

function getCurrencySymbol($currency)
{
    $currency_lists = array(
        'AFN' =>     'Afghan Afghani',
        'AFA' =>     'Afghan Afghani (1927â€“2002)',
        'ALL' =>     'Albanian Lek',
        'ALK' =>     'Albanian Lek (1946â€“1965)',
        'DZD' =>     'Algerian Dinar',
        'ADP' =>     'Andorran Peseta',
        'AOA' =>     'Angolan Kwanza',
        'AOK' =>     'Angolan Kwanza (1977â€“1991)',
        'AON' =>     'Angolan New Kwanza (1990â€“2000)',
        'AOR' =>     'Angolan Readjusted Kwanza (1995â€“1999)',
        'ARA' =>     'Argentine Austral',
        'ARS' =>     'Argentine Peso',
        'ARM' =>     'Argentine Peso (1881â€“1970)',
        'ARP' =>     'Argentine Peso (1983â€“1985)',
        'ARL' =>     'Argentine Peso Ley (1970â€“1983)',
        'AMD' =>     'Armenian Dram',
        'AWG' =>     'Aruban Florin',
        'AUD' =>     'Australian Dollar',
        'ATS' =>     'Austrian Schilling',
        'AZN' =>     'Azerbaijani Manat',
        'AZM' =>     'Azerbaijani Manat (1993â€“2006)',
        'BSD' =>     'Bahamian Dollar',
        'BHD' =>     'Bahraini Dinar',
        'BDT' =>     'Bangladeshi Taka',
        'BBD' =>     'Barbadian Dollar',
        'BYN' =>     'Belarusian Ruble',
        'BYB' =>     'Belarusian Ruble (1994â€“1999)',
        'BYR' =>     'Belarusian Ruble (2000â€“2016)',
        'BEF' =>     'Belgian Franc',
        'BEC' =>     'Belgian Franc (convertible)',
        'BEL' =>     'Belgian Franc (financial)',
        'BZD' =>     'Belize Dollar',
        'BMD' =>     'Bermudan Dollar',
        'BTN' =>     'Bhutanese Ngultrum',
        'BOB' =>     'Bolivian Boliviano',
        'BOL' =>     'Bolivian Boliviano (1863â€“1963)',
        'BOV' =>     'Bolivian Mvdol',
        'BOP' =>     'Bolivian Peso',
        'BAM' =>     'Bosnia-Herzegovina Convertible Mark',
        'BAD' =>     'Bosnia-Herzegovina Dinar (1992â€“1994)',
        'BAN' =>     'Bosnia-Herzegovina New Dinar (1994â€“1997)',
        'BWP' =>     'Botswanan Pula',
        'BRC' =>     'Brazilian Cruzado (1986â€“1989)',
        'BRZ' =>     'Brazilian Cruzeiro (1942â€“1967)',
        'BRE' =>     'Brazilian Cruzeiro (1990â€“1993)',
        'BRR' =>     'Brazilian Cruzeiro (1993â€“1994)',
        'BRN' =>     'Brazilian New Cruzado (1989â€“1990)',
        'BRB' =>     'Brazilian New Cruzeiro (1967â€“1986)',
        'BRL' =>     'Brazilian Real',
        'GBP' =>     'British Pound',
        'BND' =>     'Brunei Dollar',
        'BGL' =>     'Bulgarian Hard Lev',
        'BGN' =>     'Bulgarian Lev',
        'BGO' =>     'Bulgarian Lev (1879â€“1952)',
        'BGM' =>     'Bulgarian Socialist Lev',
        'BUK' =>     'Burmese Kyat',
        'BIF' =>     'Burundian Franc',
        'XPF' =>     'CFP Franc',
        'KHR' =>     'Cambodian Riel',
        'CAD' =>     'Canadian Dollar',
        'CVE' =>     'Cape Verdean Escudo',
        'KYD' =>     'Cayman Islands Dollar',
        'XAF' =>     'Central African CFA Franc',
        'CLE' =>     'Chilean Escudo',
        'CLP' =>     'Chilean Peso',
        'CLF' =>     'Chilean Unit of Account (UF)',
        'CNX' =>     'Chinese Peopleâ€™s Bank Dollar',
        'CNY' =>     'Chinese Yuan',
        'COP' =>     'Colombian Peso',
        'COU' =>     'Colombian Real Value Unit',
        'KMF' =>     'Comorian Franc',
        'CDF' =>     'Congolese Franc',
        'CRC' =>     'Costa Rican ColÃ³n',
        'HRD' =>     'Croatian Dinar',
        'HRK' =>     'Croatian Kuna',
        'CUC' =>     'Cuban Convertible Peso',
        'CUP' =>     'Cuban Peso',
        'CYP' =>     'Cypriot Pound',
        'CZK' =>     'Czech Koruna',
        'CSK' =>     'Czechoslovak Hard Koruna',
        'DKK' =>     'Danish Krone',
        'DJF' =>     'Djiboutian Franc',
        'DOP' =>     'Dominican Peso',
        'NLG' =>     'Dutch Guilder',
        'XCD' =>     'East Caribbean Dollar',
        'DDM' =>     'East German Mark',
        'ECS' =>     'Ecuadorian Sucre',
        'ECV' =>     'Ecuadorian Unit of Constant Value',
        'EGP' =>     'Egyptian Pound',
        'GQE' =>     'Equatorial Guinean Ekwele',
        'ERN' =>     'Eritrean Nakfa',
        'EEK' =>     'Estonian Kroon',
        'ETB' =>     'Ethiopian Birr',
        'EUR' =>     'Euro',
        'XEU' =>     'European Currency Unit',
        'FKP' =>     'Falkland Islands Pound',
        'FJD' =>     'Fijian Dollar',
        'FIM' =>     'Finnish Markka',
        'FRF' =>     'French Franc',
        'XFO' =>     'French Gold Franc',
        'XFU' =>     'French UIC-Franc',
        'GMD' =>     'Gambian Dalasi',
        'GEK' =>     'Georgian Kupon Larit',
        'GEL' =>     'Georgian Lari',
        'DEM' =>     'German Mark',
        'GHS' =>     'Ghanaian Cedi',
        'GHC' =>     'Ghanaian Cedi (1979â€“2007)',
        'GIP' =>     'Gibraltar Pound',
        'GRD' =>     'Greek Drachma',
        'GTQ' =>     'Guatemalan Quetzal',
        'GWP' =>     'Guinea-Bissau Peso',
        'GNF' =>     'Guinean Franc',
        'GNS' =>     'Guinean Syli',
        'GYD' =>     'Guyanaese Dollar',
        'HTG' =>     'Haitian Gourde',
        'HNL' =>     'Honduran Lempira',
        'HKD' =>     'Hong Kong Dollar',
        'HUF' =>     'Hungarian Forint',
        'ISK' =>     'Icelandic KrÃ³na',
        'ISJ' =>     'Icelandic KrÃ³na (1918â€“1981)',
        'INR' =>     'Indian Rupee',
        'IDR' =>     'Indonesian Rupiah',
        'IRR' =>     'Iranian Rial',
        'IQD' =>     'Iraqi Dinar',
        'IEP' =>     'Irish Pound',
        'ILS' =>     'Israeli New Shekel',
        'ILP' =>     'Israeli Pound',
        'ILR' =>     'Israeli Shekel (1980â€“1985)',
        'ITL' =>     'Italian Lira',
        'JMD' =>     'Jamaican Dollar',
        'JPY' =>     'Japanese Yen',
        'JOD' =>     'Jordanian Dinar',
        'KZT' =>     'Kazakhstani Tenge',
        'KES' =>     'Kenyan Shilling',
        'KWD' =>     'Kuwaiti Dinar',
        'KGS' =>     'Kyrgystani Som',
        'LAK' =>     'Laotian Kip',
        'LVL' =>     'Latvian Lats',
        'LVR' =>     'Latvian Ruble',
        'LBP' =>     'Lebanese Pound',
        'LSL' =>     'Lesotho Loti',
        'LRD' =>     'Liberian Dollar',
        'LYD' =>     'Libyan Dinar',
        'LTL' =>     'Lithuanian Litas',
        'LTT' =>     'Lithuanian Talonas',
        'LUL' =>     'Luxembourg Financial Franc',
        'LUC' =>     'Luxembourgian Convertible Franc',
        'LUF' =>     'Luxembourgian Franc',
        'MOP' =>     'Macanese Pataca',
        'MKD' =>     'Macedonian Denar',
        'MKN' =>     'Macedonian Denar (1992â€“1993)',
        'MGA' =>     'Malagasy Ariary',
        'MGF' =>     'Malagasy Franc',
        'MWK' =>     'Malawian Kwacha',
        'MYR' =>     'Malaysian Ringgit',
        'MVR' =>     'Maldivian Rufiyaa',
        'MVP' =>     'Maldivian Rupee (1947â€“1981)',
        'MLF' =>     'Malian Franc',
        'MTL' =>     'Maltese Lira',
        'MTP' =>     'Maltese Pound',
        'MRO' =>     'Mauritanian Ouguiya',
        'MUR' =>     'Mauritian Rupee',
        'MXV' =>     'Mexican Investment Unit',
        'MXN' =>     'Mexican Peso',
        'MXP' =>     'Mexican Silver Peso (1861â€“1992)',
        'MDC' =>     'Moldovan Cupon',
        'MDL' =>     'Moldovan Leu',
        'MCF' =>     'Monegasque Franc',
        'MNT' =>     'Mongolian Tugrik',
        'MAD' =>     'Moroccan Dirham',
        'MAF' =>     'Moroccan Franc',
        'MZE' =>     'Mozambican Escudo',
        'MZN' =>     'Mozambican Metical',
        'MZM' =>     'Mozambican Metical (1980â€“2006)',
        'MMK' =>     'Myanmar Kyat',
        'NAD' =>     'Namibian Dollar',
        'NPR' =>     'Nepalese Rupee',
        'ANG' =>     'Netherlands Antillean Guilder',
        'TWD' =>     'New Taiwan Dollar',
        'NZD' =>     'New Zealand Dollar',
        'NIO' =>     'Nicaraguan CÃ³rdoba',
        'NIC' =>     'Nicaraguan CÃ³rdoba (1988â€“1991)',
        'NGN' =>     'Nigerian Naira',
        'KPW' =>     'North Korean Won',
        'NOK' =>     'Norwegian Krone',
        'OMR' =>     'Omani Rial',
        'PKR' =>     'Pakistani Rupee',
        'PAB' =>     'Panamanian Balboa',
        'PGK' =>     'Papua New Guinean Kina',
        'PYG' =>     'Paraguayan Guarani',
        'PEI' =>     'Peruvian Inti',
        'PEN' =>     'Peruvian Sol',
        'PES' =>     'Peruvian Sol (1863â€“1965)',
        'PHP' =>     'Philippine Peso',
        'PLN' =>     'Polish Zloty',
        'PLZ' =>     'Polish Zloty (1950â€“1995)',
        'PTE' =>     'Portuguese Escudo',
        'GWE' =>     'Portuguese Guinea Escudo',
        'QAR' =>     'Qatari Rial',
        'XRE' =>     'RINET Funds',
        'RHD' =>     'Rhodesian Dollar',
        'RON' =>     'Romanian Leu',
        'ROL' =>     'Romanian Leu (1952â€“2006)',
        'RUB' =>     'Russian Ruble',
        'RUR' =>     'Russian Ruble (1991â€“1998)',
        'RWF' =>     'Rwandan Franc',
        'SVC' =>     'Salvadoran ColÃ³n',
        'WST' =>     'Samoan Tala',
        'SAR' =>     'Saudi Riyal',
        'RSD' =>     'Serbian Dinar',
        'CSD' =>     'Serbian Dinar (2002â€“2006)',
        'SCR' =>     'Seychellois Rupee',
        'SLL' =>     'Sierra Leonean Leone',
        'SGD' =>     'Singapore Dollar',
        'SKK' =>     'Slovak Koruna',
        'SIT' =>     'Slovenian Tolar',
        'SBD' =>     'Solomon Islands Dollar',
        'SOS' =>     'Somali Shilling',
        'ZAR' =>     'South African Rand',
        'ZAL' =>     'South African Rand (financial)',
        'KRH' =>     'South Korean Hwan (1953â€“1962)',
        'KRW' =>     'South Korean Won',
        'KRO' =>     'South Korean Won (1945â€“1953)',
        'SSP' =>     'South Sudanese Pound',
        'SUR' =>     'Soviet Rouble',
        'ESP' =>     'Spanish Peseta',
        'ESA' =>     'Spanish Peseta (A account)',
        'ESB' =>     'Spanish Peseta (convertible account)',
        'LKR' =>     'Sri Lankan Rupee',
        'SHP' =>     'St. Helena Pound',
        'SDD' =>     'Sudanese Dinar (1992â€“2007)',
        'SDG' =>     'Sudanese Pound',
        'SDP' =>     'Sudanese Pound (1957â€“1998)',
        'SRD' =>     'Surinamese Dollar',
        'SRG' =>     'Surinamese Guilder',
        'SZL' =>     'Swazi Lilangeni',
        'SEK' =>     'Swedish Krona',
        'CHF' =>     'Swiss Franc',
        'SYP' =>     'Syrian Pound',
        'STD' =>     'SÃ£o TomÃ© & PrÃ­ncipe Dobra',
        'TJR' =>     'Tajikistani Ruble',
        'TJS' =>     'Tajikistani Somoni',
        'TZS' =>     'Tanzanian Shilling',
        'THB' =>     'Thai Baht',
        'TPE' =>     'Timorese Escudo',
        'TOP' =>     'Tongan PaÊ»anga',
        'TTD' =>     'Trinidad & Tobago Dollar',
        'TND' =>     'Tunisian Dinar',
        'TRY' =>     'Turkish Lira',
        'TRL' =>     'Turkish Lira (1922â€“2005)',
        'TMT' =>     'Turkmenistani Manat',
        'TMM' =>     'Turkmenistani Manat (1993â€“2009)',
        'USD' =>     'US Dollar',
        'USN' =>     'US Dollar (Next day)',
        'USS' =>     'US Dollar (Same day)',
        'UGX' =>     'Ugandan Shilling',
        'UGS' =>     'Ugandan Shilling (1966â€“1987)',
        'UAH' =>     'Ukrainian Hryvnia',
        'UAK' =>     'Ukrainian Karbovanets',
        'AED' =>     'United Arab Emirates Dirham',
        'UYU' =>     'Uruguayan Peso',
        'UYP' =>     'Uruguayan Peso (1975â€“1993)',
        'UYI' =>     'Uruguayan Peso (Indexed Units)',
        'UZS' =>     'Uzbekistani Som',
        'VUV' =>     'Vanuatu Vatu',
        'VEF' =>     'Venezuelan BolÃ­var',
        'VEB' =>     'Venezuelan BolÃ­var (1871â€“2008)',
        'VND' =>     'Vietnamese Dong',
        'VNN' =>     'Vietnamese Dong (1978â€“1985)',
        'CHE' =>     'WIR Euro',
        'CHW' =>     'WIR Franc',
        'XOF' =>     'West African CFA Franc',
        'YDD' =>     'Yemeni Dinar',
        'YER' =>     'Yemeni Rial',
        'YUN' =>     'Yugoslavian Convertible Dinar (1990â€“1992)',
        'YUD' =>     'Yugoslavian Hard Dinar (1966â€“1990)',
        'YUM' =>     'Yugoslavian New Dinar (1994â€“2002)',
        'YUR' =>     'Yugoslavian Reformed Dinar (1992â€“1993)',
        'ZRN' =>     'Zairean New Zaire (1993â€“1998)',
        'ZRZ' =>     'Zairean Zaire (1971â€“1993)',
        'ZMW' =>     'Zambian Kwacha',
        'ZMK' =>     'Zambian Kwacha (1968â€“2012)',
        'ZWD' =>     'Zimbabwean Dollar (1980â€“2008)',
        'ZWR' =>     'Zimbabwean Dollar (2008)',
        'ZWL' =>     'Zimbabwean Dollar (2009)',
    );
    foreach ($currency_lists as $key => $currencies) {
        if (strtoupper($key) == strtoupper($currency) || strpos(strtoupper($currencies), strtoupper($currency))) {
            return strtoupper($key);
        }
    }
    return null;
}
