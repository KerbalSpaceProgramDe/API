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


        $entries = Shoutbox::when($since, function ($query) use ($since) {
            return $query->where('time', '>', $since);
        })
            ->when(isset($fromApi), function ($query) use ($fromApi) {
                return $query->where('fromApi', $fromApi);
            })
            ->limit(1000)->get();

        return $this->showAll($entries);
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


        $userId = -1;
        $userName = $request->input('username');

        if ($request->has('discordId')) {

            $discordId = $request->input('discordId');

            $user = DB::table('wcf1_user')->where('discordId', $discordId)->first();

            if (!is_null($user)) {
                $userId = $user->userID;
                $userName = $user->username;
            }

        }


        $entry = Shoutbox::create([
            'userID' => $userId,
            'username' => $userName,
            'message' => $request->input('message'),
            'time' => Carbon::now()->timestamp,
            'ipAddress' => $request->ip(),
            'fromApi' => true
        ]);

        return $this->showOne($entry, 201);
    }
}
