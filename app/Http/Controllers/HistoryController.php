<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TopicsResource;
use Symfony\Component\HttpFoundation\JsonResponse;

// use App\Models\NewsSubTopics;
// use App\Http\Resources\NewsTopicsResource;
// use App\Models\News;

class HistoryController extends Controller
{
    public function index($user_id)
    {
        // if (count(History::where("user_id", intval($user_id))->get()) == 0) {
        //     return response(404, "User id not found");
        // }
        $history = History::with('news', 'user')->where('user_id', intval($user_id))->orderBy("created_at", "asc")->get();
        // $history = History::join("news", "news.id", "=", "history.news_id")->join("users", "users.id", "=", "history.user_id")
        //     ->select("history.id", "history.created_at", "users.name", "news.news_title", "news.news_content", "news.news_picture_link", "news.news_picture_name")
        //     ->where('history.user_id', intval($user_id))->orderBy("created_at", "asc")->paginate(3);
        return response()->json(["history" => $history, "status" => "Success", "message" => "Succeed"], 202);
    }

    public function store(Request $request)
    {
        $news_id = $request->news_id;
        $user_id = $request->user_id;
        if (!isset($user_id)) {
            return response()->json(["status" => "Failed", "message" => "user_id empty"], 400);
        }
        if (!isset($news_id)) {
            return response()->json(["status" => "Failed", "message" => "news_id empty"], 400);
        }
        if (!User::find($user_id)) {
            return response()->json(["status" => "Failed", "message" => "user not found"], 400);
        }
        if (!News::find($news_id)) {
            return response()->json(["status" => "Failed", "message" => "news not found"], 400);
        }
        try {
            $news = History::where("news_id", $news_id)->get();
            if (sizeof($news) == 0) {
                $history_created = History::create([
                    'created_at' => round(microtime(true) * 1000),
                    'user_id' => $user_id,
                    'news_id' => $news_id
                ]);
                return response()->json(["data" => $history_created, "status" => "Success", "message" => "History stored"], 200);
            } else {
                $history_updated = History::where("news_id", $news)->update(["created_at" => round(microtime(true) * 1000)]);
                return response()->json(["data" => $history_updated, "status" => "Success", "message" => "History stored"], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => "Failed", "message" => $e->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        try {
            History::where('user_id', $id)->delete();
            return response()->json(["status" => "Success", "message" => "History deleted"], 200);
        } catch (\Exception $e) {
            return response()->json(["status" => "Failed", "message" => "DB Error"], 400);
        }
    }

    /**
     * @param \Illuminate\Http\Request @request
     * @return \Illuminate\Http\Response
     */
}
