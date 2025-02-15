<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

/**
 * ISO 3166-1 alpha-2 country codes and flags
 */

class Country
{
    public const COUNTRIES = [
            'ALL' => ['name' => 'All countries', 'flag' => '🌍'],
            'AF' => ['name' => 'Afghanistan', 'flag' => '🇦🇫'],
            'AL' => ['name' => 'Albania', 'flag' => '🇦🇱'],
            'DZ' => ['name' => 'Algeria', 'flag' => '🇩🇿'],
            'AD' => ['name' => 'Andorra', 'flag' => '🇦🇩'],
            'AO' => ['name' => 'Angola', 'flag' => '🇦🇴'],
            'AG' => ['name' => 'Antigua and Barbuda', 'flag' => '🇦🇬'],
            'AR' => ['name' => 'Argentina', 'flag' => '🇦🇷'],
            'AM' => ['name' => 'Armenia', 'flag' => '🇦🇲'],
            'AU' => ['name' => 'Australia', 'flag' => '🇦🇺'],
            'AT' => ['name' => 'Austria', 'flag' => '🇦🇹'],
            'AZ' => ['name' => 'Azerbaijan', 'flag' => '🇦🇿'],
            'BS' => ['name' => 'Bahamas', 'flag' => '🇧🇸'],
            'BH' => ['name' => 'Bahrain', 'flag' => '🇧🇭'],
            'BD' => ['name' => 'Bangladesh', 'flag' => '🇧🇩'],
            'BB' => ['name' => 'Barbados', 'flag' => '🇧🇧'],
            'BY' => ['name' => 'Belarus', 'flag' => '🇧🇾'],
            'BE' => ['name' => 'Belgium', 'flag' => '🇧🇪'],
            'BZ' => ['name' => 'Belize', 'flag' => '🇧🇿'],
            'BJ' => ['name' => 'Benin', 'flag' => '🇧🇯'],
            'BT' => ['name' => 'Bhutan', 'flag' => '🇧🇹'],
            'BO' => ['name' => 'Bolivia', 'flag' => '🇧🇴'],
            'BA' => ['name' => 'Bosnia and Herzegovina', 'flag' => '🇧🇦'],
            'BW' => ['name' => 'Botswana', 'flag' => '🇧🇼'],
            'BR' => ['name' => 'Brazil', 'flag' => '🇧🇷'],
            'BN' => ['name' => 'Brunei', 'flag' => '🇧🇳'],
            'BG' => ['name' => 'Bulgaria', 'flag' => '🇧🇬'],
            'BF' => ['name' => 'Burkina Faso', 'flag' => '🇧🇫'],
            'BI' => ['name' => 'Burundi', 'flag' => '🇧🇮'],
            'KH' => ['name' => 'Cambodia', 'flag' => '🇰🇭'],
            'CM' => ['name' => 'Cameroon', 'flag' => '🇨🇲'],
            'CA' => ['name' => 'Canada', 'flag' => '🇨🇦'],
            'CV' => ['name' => 'Cape Verde', 'flag' => '🇨🇻'],
            'CF' => ['name' => 'Central African Republic', 'flag' => '🇨🇫'],
            'TD' => ['name' => 'Chad', 'flag' => '🇹🇩'],
            'CL' => ['name' => 'Chile', 'flag' => '🇨🇱'],
            'CN' => ['name' => 'China', 'flag' => '🇨🇳'],
            'CO' => ['name' => 'Colombia', 'flag' => '🇨🇴'],
            'KM' => ['name' => 'Comoros', 'flag' => '🇰🇲'],
            'CG' => ['name' => 'Congo', 'flag' => '🇨🇬'],
            'CD' => ['name' => 'Congo, Democratic Republic', 'flag' => '🇨🇩'],
            'CR' => ['name' => 'Costa Rica', 'flag' => '🇨🇷'],
            'HR' => ['name' => 'Croatia', 'flag' => '🇭🇷'],
            'CU' => ['name' => 'Cuba', 'flag' => '🇨🇺'],
            'CY' => ['name' => 'Cyprus', 'flag' => '🇨🇾'],
            'CZ' => ['name' => 'Czech Republic', 'flag' => '🇨🇿'],
            'DK' => ['name' => 'Denmark', 'flag' => '🇩🇰'],
            'DJ' => ['name' => 'Djibouti', 'flag' => '🇩🇯'],
            'DM' => ['name' => 'Dominica', 'flag' => '🇩🇲'],
            'DO' => ['name' => 'Dominican Republic', 'flag' => '🇩🇴'],
            'EC' => ['name' => 'Ecuador', 'flag' => '🇪🇨'],
            'EG' => ['name' => 'Egypt', 'flag' => '🇪🇬'],
            'SV' => ['name' => 'El Salvador', 'flag' => '🇸🇻'],
            'GQ' => ['name' => 'Equatorial Guinea', 'flag' => '🇬🇶'],
            'ER' => ['name' => 'Eritrea', 'flag' => '🇪🇷'],
            'EE' => ['name' => 'Estonia', 'flag' => '🇪🇪'],
            'ET' => ['name' => 'Ethiopia', 'flag' => '🇪🇹'],
            'FJ' => ['name' => 'Fiji', 'flag' => '🇫🇯'],
            'FI' => ['name' => 'Finland', 'flag' => '🇫🇮'],
            'FR' => ['name' => 'France', 'flag' => '🇫🇷'],
            'GA' => ['name' => 'Gabon', 'flag' => '🇬🇦'],
            'GM' => ['name' => 'Gambia', 'flag' => '🇬🇲'],
            'GE' => ['name' => 'Georgia', 'flag' => '🇬🇪'],
            'DE' => ['name' => 'Germany', 'flag' => '🇩🇪'],
            'GH' => ['name' => 'Ghana', 'flag' => '🇬🇭'],
            'GR' => ['name' => 'Greece', 'flag' => '🇬🇷'],
            'GD' => ['name' => 'Grenada', 'flag' => '🇬🇩'],
            'GT' => ['name' => 'Guatemala', 'flag' => '🇬🇹'],
            'GN' => ['name' => 'Guinea', 'flag' => '🇬🇳'],
            'GW' => ['name' => 'Guinea-Bissau', 'flag' => '🇬🇼'],
            'GY' => ['name' => 'Guyana', 'flag' => '🇬🇾'],
            'HT' => ['name' => 'Haiti', 'flag' => '🇭🇹'],
            'HN' => ['name' => 'Honduras', 'flag' => '🇭🇳'],
            'HU' => ['name' => 'Hungary', 'flag' => '🇭🇺'],
            'IS' => ['name' => 'Iceland', 'flag' => '🇮🇸'],
            'IN' => ['name' => 'India', 'flag' => '🇮🇳'],
            'ID' => ['name' => 'Indonesia', 'flag' => '🇮🇩'],
            'IR' => ['name' => 'Iran', 'flag' => '🇮🇷'],
            'IQ' => ['name' => 'Iraq', 'flag' => '🇮🇶'],
            'IE' => ['name' => 'Ireland', 'flag' => '🇮🇪'],
            'IL' => ['name' => 'Israel', 'flag' => '🇮🇱'],
            'IT' => ['name' => 'Italy', 'flag' => '🇮🇹'],
            'JM' => ['name' => 'Jamaica', 'flag' => '🇯🇲'],
            'JP' => ['name' => 'Japan', 'flag' => '🇯🇵'],
            'JO' => ['name' => 'Jordan', 'flag' => '🇯🇴'],
            'KZ' => ['name' => 'Kazakhstan', 'flag' => '🇰🇿'],
            'KE' => ['name' => 'Kenya', 'flag' => '🇰🇪'],
            'KI' => ['name' => 'Kiribati', 'flag' => '🇰🇮'],
            'KP' => ['name' => 'Korea, North', 'flag' => '🇰🇵'],
            'KR' => ['name' => 'Korea, South', 'flag' => '🇰🇷'],
            'KW' => ['name' => 'Kuwait', 'flag' => '🇰🇼'],
            'KG' => ['name' => 'Kyrgyzstan', 'flag' => '🇰🇬'],
            'LA' => ['name' => 'Laos', 'flag' => '🇱🇦'],
            'LV' => ['name' => 'Latvia', 'flag' => '🇱🇻'],
            'LB' => ['name' => 'Lebanon', 'flag' => '🇱🇧'],
            'LS' => ['name' => 'Lesotho', 'flag' => '🇱🇸'],
            'LR' => ['name' => 'Liberia', 'flag' => '🇱🇷'],
            'LY' => ['name' => 'Libya', 'flag' => '🇱🇾'],
            'LI' => ['name' => 'Liechtenstein', 'flag' => '🇱🇮'],
            'LT' => ['name' => 'Lithuania', 'flag' => '🇱🇹'],
            'LU' => ['name' => 'Luxembourg', 'flag' => '🇱🇺'],
            'MK' => ['name' => 'North Macedonia', 'flag' => '🇲🇰'],
            'MG' => ['name' => 'Madagascar', 'flag' => '🇲🇬'],
            'MW' => ['name' => 'Malawi', 'flag' => '🇲🇼'],
            'MY' => ['name' => 'Malaysia', 'flag' => '🇲🇾'],
            'MV' => ['name' => 'Maldives', 'flag' => '🇲🇻'],
            'ML' => ['name' => 'Mali', 'flag' => '🇲🇱'],
            'MT' => ['name' => 'Malta', 'flag' => '🇲🇹'],
            'MH' => ['name' => 'Marshall Islands', 'flag' => '🇲🇭'],
            'MR' => ['name' => 'Mauritania', 'flag' => '🇲🇷'],
            'MU' => ['name' => 'Mauritius', 'flag' => '🇲🇺'],
            'MX' => ['name' => 'Mexico', 'flag' => '🇲🇽'],
            'FM' => ['name' => 'Micronesia', 'flag' => '🇫🇲'],
            'MD' => ['name' => 'Moldova', 'flag' => '🇲🇩'],
            'MC' => ['name' => 'Monaco', 'flag' => '🇲🇨'],
            'MN' => ['name' => 'Mongolia', 'flag' => '🇲🇳'],
            'ME' => ['name' => 'Montenegro', 'flag' => '🇲🇪'],
            'MA' => ['name' => 'Morocco', 'flag' => '🇲🇦'],
            'MZ' => ['name' => 'Mozambique', 'flag' => '🇲🇿'],
            'MM' => ['name' => 'Myanmar', 'flag' => '🇲🇲'],
            'NA' => ['name' => 'Namibia', 'flag' => '🇳🇦'],
            'NR' => ['name' => 'Nauru', 'flag' => '🇳🇷'],
            'NP' => ['name' => 'Nepal', 'flag' => '🇳🇵'],
            'NL' => ['name' => 'Netherlands', 'flag' => '🇳🇱'],
            'NZ' => ['name' => 'New Zealand', 'flag' => '🇳🇿'],
            'NI' => ['name' => 'Nicaragua', 'flag' => '🇳🇮'],
            'NE' => ['name' => 'Niger', 'flag' => '🇳🇪'],
            'NG' => ['name' => 'Nigeria', 'flag' => '🇳🇬'],
            'NO' => ['name' => 'Norway', 'flag' => '🇳🇴'],
            'OM' => ['name' => 'Oman', 'flag' => '🇴🇲'],
            'PK' => ['name' => 'Pakistan', 'flag' => '🇵🇰'],
            'PW' => ['name' => 'Palau', 'flag' => '🇵🇼'],
            'PS' => ['name' => 'Palestine', 'flag' => '🇵🇸'],
            'PA' => ['name' => 'Panama', 'flag' => '🇵🇦'],
            'PG' => ['name' => 'Papua New Guinea', 'flag' => '🇵🇬'],
            'PY' => ['name' => 'Paraguay', 'flag' => '🇵🇾'],
            'PE' => ['name' => 'Peru', 'flag' => '🇵🇪'],
            'PH' => ['name' => 'Philippines', 'flag' => '🇵🇭'],
            'PL' => ['name' => 'Poland', 'flag' => '🇵🇱'],
            'PT' => ['name' => 'Portugal', 'flag' => '🇵🇹'],
            'QA' => ['name' => 'Qatar', 'flag' => '🇶🇦'],
            'RO' => ['name' => 'Romania', 'flag' => '🇷🇴'],
            'RU' => ['name' => 'Russia', 'flag' => '🇷🇺'],
            'RW' => ['name' => 'Rwanda', 'flag' => '🇷🇼'],
            'KN' => ['name' => 'Saint Kitts and Nevis', 'flag' => '🇰🇳'],
            'LC' => ['name' => 'Saint Lucia', 'flag' => '🇱🇨'],
            'VC' => ['name' => 'Saint Vincent and the Grenadines', 'flag' => '🇻🇨'],
            'WS' => ['name' => 'Samoa', 'flag' => '🇼🇸'],
            'SM' => ['name' => 'San Marino', 'flag' => '🇸🇲'],
            'ST' => ['name' => 'Sao Tome and Principe', 'flag' => '🇸🇹'],
            'SA' => ['name' => 'Saudi Arabia', 'flag' => '🇸🇦'],
            'SN' => ['name' => 'Senegal', 'flag' => '🇸🇳'],
            'RS' => ['name' => 'Serbia', 'flag' => '🇷🇸'],
            'SC' => ['name' => 'Seychelles', 'flag' => '🇸🇨'],
            'SL' => ['name' => 'Sierra Leone', 'flag' => '🇸🇱'],
            'SG' => ['name' => 'Singapore', 'flag' => '🇸🇬'],
            'SK' => ['name' => 'Slovakia', 'flag' => '🇸🇰'],
            'SI' => ['name' => 'Slovenia', 'flag' => '🇸🇮'],
            'SB' => ['name' => 'Solomon Islands', 'flag' => '🇸🇧'],
            'SO' => ['name' => 'Somalia', 'flag' => '🇸🇴'],
            'ZA' => ['name' => 'South Africa', 'flag' => '🇿🇦'],
            'SS' => ['name' => 'South Sudan', 'flag' => '🇸🇸'],
            'ES' => ['name' => 'Spain', 'flag' => '🇪🇸'],
            'LK' => ['name' => 'Sri Lanka', 'flag' => '🇱🇰'],
            'SD' => ['name' => 'Sudan', 'flag' => '🇸🇩'],
            'SR' => ['name' => 'Suriname', 'flag' => '🇸🇷'],
            'SE' => ['name' => 'Sweden', 'flag' => '🇸🇪'],
            'CH' => ['name' => 'Switzerland', 'flag' => '🇨🇭'],
            'SY' => ['name' => 'Syria', 'flag' => '🇸🇾'],
            'TW' => ['name' => 'Taiwan', 'flag' => '🇹🇼'],
            'TJ' => ['name' => 'Tajikistan', 'flag' => '🇹🇯'],
            'TZ' => ['name' => 'Tanzania', 'flag' => '🇹🇿'],
            'TH' => ['name' => 'Thailand', 'flag' => '🇹🇭'],
            'TL' => ['name' => 'Timor-Leste', 'flag' => '🇹🇱'],
            'TG' => ['name' => 'Togo', 'flag' => '🇹🇬'],
            'TO' => ['name' => 'Tonga', 'flag' => '🇹🇴'],
            'TT' => ['name' => 'Trinidad and Tobago', 'flag' => '🇹🇹'],
            'TN' => ['name' => 'Tunisia', 'flag' => '🇹🇳'],
            'TR' => ['name' => 'Turkey', 'flag' => '🇹🇷'],
            'TM' => ['name' => 'Turkmenistan', 'flag' => '🇹🇲'],
            'TV' => ['name' => 'Tuvalu', 'flag' => '🇹🇻'],
            'UG' => ['name' => 'Uganda', 'flag' => '🇺🇬'],
            'UA' => ['name' => 'Ukraine', 'flag' => '🇺🇦'],
            'AE' => ['name' => 'United Arab Emirates', 'flag' => '🇦🇪'],
            'GB' => ['name' => 'United Kingdom', 'flag' => '🇬🇧'],
            'US' => ['name' => 'United States', 'flag' => '🇺🇸'],
            'UY' => ['name' => 'Uruguay', 'flag' => '🇺🇾'],
            'UZ' => ['name' => 'Uzbekistan', 'flag' => '🇺🇿'],
            'VU' => ['name' => 'Vanuatu', 'flag' => '🇻🇺'],
            'VA' => ['name' => 'Vatican City', 'flag' => '🇻🇦'],
            'VE' => ['name' => 'Venezuela', 'flag' => '🇻🇪'],
            'VN' => ['name' => 'Vietnam', 'flag' => '🇻🇳'],
            'YE' => ['name' => 'Yemen', 'flag' => '🇾🇪'],
            'ZM' => ['name' => 'Zambia', 'flag' => '🇿🇲'],
            'ZW' => ['name' => 'Zimbabwe', 'flag' => '🇿🇼']
    ];

    public static function getByCode(string $code): ?array
    {
        return static::COUNTRIES[strtoupper($code)] ?? null;
    }

    public static function getNameByCode(string $code): ?string
    {
        return static::getByCode($code)['name'] ?? null;
    }

    public static function getFlagByCode(string $code): ?string
    {
        return static::getByCode($code)['flag'] ?? null;
    }

    public static function getAllCountries(): Collection
    {
        return collect(static::COUNTRIES)->map(function ($country, $code) {
            return array_merge(['code' => $code], $country);
        });
    }

    public static function searchByName(string $name): Collection
    {
        return static::getAllCountries()->filter(function ($country) use ($name) {
            return str_contains(strtolower($country['name']), strtolower($name));
        });
    }

    public static function isValidCountryCode(string $code): bool
    {
        return isset(static::COUNTRIES[strtoupper($code)]);
    }
}
