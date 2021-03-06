<?php

namespace App\Http\Controllers;


use App\Shoutbox;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoutboxController extends Controller
{

    use ApiResponser;

    /**
     * List all Shoutbox entries. Hard limit is 1000.
     *
     * @param Request $request
     * @return mixed
     *
     */
    public function index(Request $request)
    {

        /**
         * @TODO
         */
        $request->validate([
            'since_time' => 'integer',
            'fromApi' => 'boolean',
        ]);


        $fromApi = null;

        $since = (int)$request->input('since_time');


        if ($request->has('fromApi')) {
            $fromApi = $request->input('fromApi');
        }



        return DB::table('wcf1_shoutbox_entry')
            ->when($since, function ($query) use ($since) {
                return $query->where('time', '>', $since);
            })
            ->when(isset($fromApi), function ($query) use ($fromApi) {
                return $query->where('fromApi', $fromApi);
            })
            ->limit(1000)->get();

    }

    /**
     * Return one entry
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $entry = Shoutbox::findOrFail($id);

        return $this->showOne($entry);
    }

    /**
     * Create a new entry
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $rules = [
            'username' => 'required',
            'message' => 'required',
        ];

        $this->validate($request, $rules);

        $entry = Shoutbox::create([
            'userID' => -1,
            'username' => $request->input('username'),
            'message' => $request->input('message'),
            'time' => Carbon::now()->timestamp,
            'ipAddress' => $request->ip(),
            'fromApi' => true
        ]);

        return $this->showOne($entry, 201);
    }
}
