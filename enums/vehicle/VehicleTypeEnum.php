<?php

namespace app\enums\vehicle;

enum VehicleTypeEnum: int
{
    case UNINITIALIZED = -1;

    case BMW_I7 = 1;

    case GLE_MERCEDES = 2;

    case MERCEDES_BUS_50_SEATED = 3;

    case RANGE_ROVER_EVOQUE = 4;

    case BUSINESS_CLASS_SEDAN_MERCEDES = 5;

    case BUSINESS_VAN_LUXURY_SEDAN_MERCEDES = 6;

    case ECONOMY_CLASS_SEDAN_MERCEDES = 7;

    case JET_CLASS_LUXURY_MINIVAN_MERCEDES = 8;

    case STANDARD_MINI_BUS_MERCEDES = 9;

    case STANDARD_CLASS_SEDAN_MERCEDES = 10;

    case STANDARD_VAN_MERCEDES = 11;

    case RANGE_ROVER = 12;

    case MERCEDES_S_CLASS = 13;

    case MERCEDES_V_CLASS_AMG = 14;

    case MERCEDES_V_CLASS = 15;

    case MERCEDES_VIANO = 16;

    case MERCEDES_SPRINTER_10_PAX = 17;

    case MERCEDES_SPRINTER_18_PAX = 18;

    case MERCEDES_E_CLASS = 19;


    public function label(): string
    {
        return match ($this) {
            self::UNINITIALIZED => 'uninitialized',

            self:: BMW_I7 => 'BMW I7',

            self::GLE_MERCEDES => 'GLE MERCEDES',

            self::MERCEDES_BUS_50_SEATED => 'MERCEDES BUS 50 SEATED',

            self::RANGE_ROVER_EVOQUE => 'RANGE ROVER EVOQUE',

            self::BUSINESS_CLASS_SEDAN_MERCEDES => 'BUSINESS CLASS SEDAN MERCEDES',

            self::BUSINESS_VAN_LUXURY_SEDAN_MERCEDES => 'BUSINESS VAN LUXURY MERCEDES',

            self::ECONOMY_CLASS_SEDAN_MERCEDES => 'ECONOMY CLASS SEDAN MERCEDES',

            self::JET_CLASS_LUXURY_MINIVAN_MERCEDES => 'JET CLASS LUXURY MINIVAN MERCEDES',

            self::STANDARD_MINI_BUS_MERCEDES => 'STANDARD MINI BUS MERCEDES',

            self::STANDARD_CLASS_SEDAN_MERCEDES => 'STANDARD CLASS SEDAN MERCEDES',

            self::STANDARD_VAN_MERCEDES => 'STANDARD VAN MERCEDES',

            self::RANGE_ROVER => 'RANGE ROVER',

            self::MERCEDES_S_CLASS => 'MERCEDES S CLASS',

            self::MERCEDES_V_CLASS_AMG => 'MERCEDES V CLASS AMG',

            self::MERCEDES_V_CLASS => 'MERCEDES V CLASS',

            self::MERCEDES_VIANO => 'MERCEDES VIANO',

            self::MERCEDES_SPRINTER_10_PAX => 'MERCEDES SPRINTER 10 PAX',

            self::MERCEDES_SPRINTER_18_PAX => 'MERCEDES SPRINTER 18 PAX',

            self::MERCEDES_E_CLASS => 'MERCEDES E CLASS',

        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


    public static function getMapping($vehicleTypeKeyString): ?int
    {

        if (!$vehicleTypeKeyString) {
            return self::UNINITIALIZED->value;
        }

        $vehicleTypeKeyString = strtolower(str_replace(' ', '', trim($vehicleTypeKeyString)));
        $mappingArray = [

             'bmwi7' =>self:: BMW_I7 ->value,

             'gle' =>self::GLE_MERCEDES->value,

             'bus50seated' =>self::MERCEDES_BUS_50_SEATED->value,

             'rvevoque' =>self::RANGE_ROVER_EVOQUE->value,

             'app-bc' =>self::BUSINESS_CLASS_SEDAN_MERCEDES->value,

             'app-bv' =>self::BUSINESS_VAN_LUXURY_SEDAN_MERCEDES ->value,

             'app-ec' =>self::ECONOMY_CLASS_SEDAN_MERCEDES->value,

             'app-jcmv' =>self::JET_CLASS_LUXURY_MINIVAN_MERCEDES->value,

             'app-smb' =>self::STANDARD_MINI_BUS_MERCEDES->value,

             'app-sc' =>self::STANDARD_CLASS_SEDAN_MERCEDES->value,

             'app=sv' =>self::STANDARD_VAN_MERCEDES->value,

             'rv' =>self::RANGE_ROVER ->value,

             'sclass' =>self::MERCEDES_S_CLASS ->value,

             'vclass' =>self::MERCEDES_V_CLASS_AMG->value,

             'v' =>self::MERCEDES_V_CLASS ->value,

             'viano' =>self::MERCEDES_VIANO->value,

             'sprin10pax' =>self::MERCEDES_SPRINTER_10_PAX->value,

             'sprin.18pax' =>self::MERCEDES_SPRINTER_18_PAX->value,

             'eclass' =>self::MERCEDES_E_CLASS->value,


        ];

        return $mappingArray[$vehicleTypeKeyString] ?? self::UNINITIALIZED->value;
    }
}
