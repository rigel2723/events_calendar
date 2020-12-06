<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use App\Models\CalendarEvents;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\Exception;

class EventsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $data = CalendarEvents::all()->toArray();
        return response()->json(['code' => 200, 'msg' => 'Success', 'data' => $data]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_event(Request $request) {
        $eventName = ucwords(strtolower($request->input('eventName')));
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $checkedDay = $request->input('checkedDay');

        // GET ALL DAYS IN RANGE
        $eventDates =  Helper::getDaysInRange($startDate,$endDate, $checkedDay);
        if (!$eventDates) {
            return response()->json(['code' => '201', 'msg' => 'No Dates in range']);
        }

        // GET ALL SAME EVENTS IN DATABASE TO REMOVE IN SAVE LIST
        $getSameEvents = CalendarEvents::select('event_date', 'bgcolor')
            ->whereIn('event_date', $eventDates)
            ->where('event_name', $eventName)
            ->get()
            ->toArray();
        $unsetDates = Helper::toArrayList($getSameEvents, 'event_date');

        $bgColor = Helper::generateHexColor();
        if (COUNT($unsetDates) > 0) {
            $bgColor = $getSameEvents[0]['bgcolor'];
            if (COUNT($unsetDates) >= COUNT($eventDates)) {
                return response()->json(['code' => 201, 'msg' => 'Event already exists']);
            }
        }

        // SET THE FORMAT OF DATA TO SAVE
        $data = Helper::setArrSaveData($eventName, $bgColor, $eventDates, $unsetDates);

        try {
            CalendarEvents::insert($data);
        } catch (Exception $e) {
            return response()->json(['code' => 201, 'msg' => $e->getMessage()]);
        }

        return response()->json(['code' => 200, 'msg' => 'Success']);
    }
}
