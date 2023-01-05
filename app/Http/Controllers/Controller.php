<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\AdminApproval;
use App\Models\User;
use App\Models\News;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * success response method
     * @return \Illuminate\Http\Response;
     */
    public function renderComponentsInAuthorNewsDashboard(int $user_id)
    {
        $news = News::join("users", "users.id", "=", "news.user_id")->where("news.user_id", $user_id)->select("news.*", "users.id", "users.name", "users.balance_account_number")->first();
        $user = AdminApproval::join("users", "users.id", "=", "admin_approval.user_id")->where("admin_approval.user_id", $user_id)->select("admin_approval.*", "users.id", "users.name", "users.balance_account_number")->first();
        return response()->json(["news" => $news, "user" => $user], 202);
    }
    // public function renderComponentsInAuthorNewsDashboard(int $user_id)
    // {
    //     if (!User::where("id", intval($user_id))->first()) {
    //         return response("User is not found", 404);
    //     } else {
    //         // if (AdminApproval::where("user_id", intval($user_id))->first()) {
    //         //     return response("You have requested to register as an author. Wait until we accept your request", 202);
    //         // }
    //         if (!(AdminApproval::where("user_id", intval($user_id))->first())) {
    //             return response()->json(['status' => "fail", "error_type" => "user has not been approve"], 404);
    //         }
    //         if (User::where("id", $user_id)->first()) {
    //             $balance_number = User::where("id", $user_id)->first()->balance_account_number;
    //             if (!(User::where("balance_account_number", $balance_number)->first()) == 0) {
    //                 return response()->json(['status' => "fail", "error_type" => "author have no balance account number"], 404);
    //             }
    //         }
    //         if (!(News::where("user_id", $user_id)->get()) == 0) {
    //             return response()->json(['status' => "fail", "error_type" => "author have no news"], 404);
    //         }
    //     }
    // }
}
