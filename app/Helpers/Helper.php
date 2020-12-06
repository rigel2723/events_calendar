<?php
namespace App\Helpers;

class Helper
{
    /**
     * @param String $startDate
     * @param String $endDate
     * @param array $data
     * @return array
     * **NOTE:
     *      RETURNS THE SELECTED DAYS IN DATE RANGE
     *      0 = SUNDAY; 6 = SATURDAY
     */
    public static function getDaysInRange(String $startDate = null, String $endDate = null, Array $selectedDays = null) {

        if (!$startDate || !$endDate || !$selectedDays) {
            return [];
        }

        $startDate = strtotime($startDate . ' 00:00:00');
        $endDate = strtotime($endDate . ' 00:00:00');

        $days = [];
        for ($date = $startDate; $date <= $endDate; $date += 60 * 60 * 24) {
            if (in_array(strftime('%w', $date), $selectedDays)) {
                $days[] = strftime('%Y-%m-%d', $date);
            }
        }
        return $days;
    }

    /**
     * @param String $eventName
     * @param String $bgColor
     * @param array $eventDates
     * @param array $unsetDates
     * @return array
     * SET DATA FORMAT TO BE SAVED IN DATABASE
     */
    public static function setArrSaveData(String $eventName, String $bgColor, Array $eventDates, Array $unsetDates = []) {
        $saveData = [];
        foreach ($eventDates as $edates) {
            if (!in_array($edates, $unsetDates)) {
                $saveData[] = [
                    'event_name' => $eventName,
                    'event_date' => $edates,
                    'bgcolor' => $bgColor
                ];
            }
        }
        return $saveData;
    }

    /**
     * @param array $data
     * @param String $colName
     * @return array
     * RETURN DATA AS ARRAY
     */
    public static function toArrayList(Array $data = [], String $colName = '') {
        if ($data && $colName) {
            $newData = [];
            foreach ($data as $val) {
                $newData[] = $val[$colName];
            }
            return $newData;
        }
        return [];
    }

    public static function generateHexColor() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}