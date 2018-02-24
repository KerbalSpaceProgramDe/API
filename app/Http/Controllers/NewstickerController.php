<?php

namespace App\Http\Controllers;


use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewstickerController extends Controller
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

        $request->validate([
            'since_time' => 'integer',
        ]);


        $fromApi = null;

        $since = (int)$request->input('since_time');

        $posts = DB::table('wbb1_thread')
            ->join('wbb1_post', 'wbb1_post.threadID', '=', 'wbb1_thread.threadID')
            ->join('wcf1_user', 'wbb1_post.userID', '=', 'wcf1_user.userID')
            ->leftJoin('wcf1_user_avatar', 'wcf1_user_avatar.avatarID', '=', 'wcf1_user.avatarID')
            ->select(DB::raw("
                IF(firstPostID = lastPostID, 'THREAD', 'POST') type,
              wbb1_post.postID,
              wbb1_thread.threadID,
              wbb1_post.time,
              wbb1_post.username,
              wbb1_thread.topic,
              LEFT(wbb1_post.message, 1000) message,
              CONCAT('https://www.kerbalspaceprogram.de/wcf/images/avatars/', LEFT(fileHash,2),'/', wcf1_user_avatar.avatarID,'-', fileHash, '.', avatarExtension) avatarurl
            "))
            ->where('wbb1_post.time', '>', $since)
            ->limit(100)
            ->get();

        return $this->showAll($posts);
    }


}
