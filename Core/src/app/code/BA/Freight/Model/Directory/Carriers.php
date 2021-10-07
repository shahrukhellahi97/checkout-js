<?php
namespace BA\Freight\Model\Directory;

class Carriers
{
    private const CARRIERS = [
        5 => 'Customer Goods collect by Cust carrier',
        9 => 'Royal Mail 1st Class Post',
        10 => 'Royal Mail 2nd Class Post',
        11 => 'Royal Mail Special Delivery',
        12 => 'Royal Mail RMSD',
        17 => 'TNT International',
        18 => 'TNT Overnight',
        37 => 'DPD 4/5 Day',
        41 => 'U.P.S UPS Express Plus',
        42 => 'Customer Customers own manifest',
        43 => 'DHL Export Express WorldWide NON-EU',
        44 => 'DHL Export Economy Select-EU destinations',
        57 => 'TNT Hub & Spoke',
        58 => 'Interlink 2 day service',
        59 => 'Interlink Next day pre 12:00pm',
        66 => 'Intl Economy',
        75 => 'Interlink Next day pre 9:30am',
        76 => 'DHL Export Express 9:00 - B4 9am',
        77 => 'DHL Export Express 12:00 - B4 12 Noon',
        78 => 'U.P.S UPS Standard',
        79 => 'U.P.S UPS Express',
        80 => 'U.P.S UPS Express Saver',
        85 => 'Royal Mail Packet Post',
        86 => 'Interlink Next day service',
        87 => 'AK Worthington Next Day',
        88 => 'AK Worthington Specified Time',
        89 => 'Speed Same Day',
        90 => 'Geodis Roadfreight - Pallet(s)',
        91 => 'GLS National Standard',
        92 => 'GLS Europe',
        93 => 'GLS National Gefahrgut',
        97 => 'K&N Airfreight',
        98 => 'K&N Seafreight',
        99 => 'K&N Roadfreight',
        100 => 'U.P.S UPS UK Pre-Noon',
        101 => 'DHL UK 1-3 Day Standard Service',
        102 => 'DHL UK Pre 12am (subject to postcode)',
        103 => 'DHL UK Pre 9am (subject to postcode)',
        105 => 'DHL Express (Intel Acc) Economy Service',
        106 => 'DHL Global Forwarding Airfreight',
        107 => 'DHL Global Forwarding Roadfreight',
        108 => 'DHL Global Forwarding Seafreight',
        109 => 'DHL Export Economy Select Non-EU',
        110 => 'DHL Export Express WorldWide - EU',
        111 => 'Brand Addition Internal',
        112 => 'Brand Addition MultiShip',
        113 => 'DPD Standard Service',
        114 => 'DPD Pre 12:00pm',
        115 => 'Schenker National',
        116 => 'Schenker International',
        117 => 'ITC National',
        118 => 'ITC International',
        119 => 'Schmidt-Gevelsberg National',
        120 => 'Schmidt-Gevelsberg International',
        121 => 'Expeditors Airfreight',
        122 => 'Expeditors Roadfreight',
        123 => 'Expeditors Seafreight',
        124 => 'Benfleet Roadfreight',
        125 => 'Cardinal-Plus Ltd Airfreight',
        126 => 'Cardinal-Plus Ltd Seafreight',
        127 => 'Cardinal-Plus Ltd Roadfreight',
        128 => 'Fedex Workday UK & Intl Employee 850089922',
        129 => 'Fedex Workday Retail Shipping',
        130 => 'DHL Germany Standard',
        131 => 'DHL Germany Express',
        132 => 'K&N Courier (Fedex)',
        133 => 'Panalpina Roadfreight',
        134 => 'DHL UK Saturday Delivery',
        135 => 'DPD Saturday Pre 12:00pm',
        136 => 'DPD Saturday Pre 10:30am',
        137 => 'DPD Saturday Delivery',
        138 => 'DPD Sunday Delivery',
        139 => 'DPD Next Day NI,Scot Isl,Chan Isl',
        140 => 'DPD 2 Day NI,Scot Isl,Chan Isl',
        141 => 'DPD UK Next Day Standard Service',
        142 => 'DPD Germany Express',
        143 => 'Damco Service',
        145 => 'Fedex UK Int\'l Priority',
        146 => 'Norman Global Logistics Seafreight',
        147 => 'Norman Global Logistics Airfreight',
        148 => 'Panalpina Seafreight',
        152 => 'HOLD',
        185 => 'DHL Express (Intel Acc) ROW Economy Service',
        2121 => 'DPD Pre 10:30am',
        2122 => 'DPD Predict',
        2123 => 'Holenstein Umlagerung'
    ];

    public function getAllCariers()
    {
        $sort = self::CARRIERS;

        asort($sort);

        return $sort;
    }

    public function getCarrierById($id)
    {
        return isset(self::CARRIERS[$id]) ? self::CARRIERS[$id] : null;
    }
}