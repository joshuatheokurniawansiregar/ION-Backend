<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\News;
use App\Models\Topics;
use App\Models\SubTopics;
use App\Models\AdminNewsApproval;
use App\Models\User;
use App\Http\Controllers\Controller as Controller;
use App\Notifications\EmailNewsApprovalNotification;
use Exception;

// Eager: Join
// Lazy: Not Join
class NewsController extends Controller
{

    // convert this 1670906391017 to date on php where the date format is '%yyy-%m-%d'
    // $timestamp = 1670906391017;
    // $date = date('%yyy-%m-%d', $timestamp);

    // On javascript
    //     const timestamp = 1670906391017;
    // const date = new Date(timestamp).toLocaleDateString('en-US', {
    //   year: 'numeric',
    //   month: '2-digit',
    //   day: '2-digit'
    // });

    // function index(){
    //     \DB::enableQueryLog();
    //     $show_news = DB::select("SELECT `title`, `news_content` FROM `news`");
    //     return $this->sendRespond($show_news, "News are showed", 200);
    //     dd(DB::getQueryLog());

    //     $test = NewsObject::query()->get();
    //     return $test;
    //     dd(\DB::getQueryLog());
    // }

    //Admin
    private string $randomTitle = "";
    public function __construct()
    {
        $this->middleware('throttle:1,0.3', ['except' => ['update', 'showRelatedNews', 'reject', 'showNewsByTopics', 'searchNewsByNewsTitle', 'openNewsPicture', 'checkNewsExist', 'detail', 'showNewsByUserId', 'readingNews', 'showNewsBySubTopicsAndTopics', 'showNewsByOneTopic', 'index']]);
    }

    public function  random_string(): string
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $random = substr(md5(str_shuffle($characters)), 0, 7);
        return $random;
    }

    public function index()
    {
        $news = News::join("users", "users.id", "=", "news.user_id")->select("news.*", "users.name", "users.photo_profile_link")->where("role", "author")->get();
        return response()->json($news, 200);
    }
    public function detail($id)
    {
        $news = News::where('news.id', $id)->join("users", "users.id", "=", "news.user_id")->select("news.*", "users.name", "users.photo_profile_link")->where("role", "author")->get();
        return response()->json($news, 200);
    }
    public function searchNewsByNewsTitle($keywordparam)
    {
        $news = News::join("users", "users.id", "=", "news.user")->where("news_content", "like", str_replace("%", "", $keywordparam))->get();
        return response()->json($news);
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function approve(int $approval_id)
    {
        $approval_ids = AdminNewsApproval::findOrFail($approval_id);
        if ($approval_ids != null) {
            $date_now = round(microtime(true) * 1000);
            $approval_ids = AdminNewsApproval::findOrFail($approval_id);
            $news_new = $approval_ids->news_title;
            $news_content = $approval_ids->news_content;
            $news_slug = $approval_ids->news_slug;
            $news_picture_link = $approval_ids->news_picture_link;
            $news_picture_name = $approval_ids->news_picture_name;
            $news_picture_path = $approval_ids->news_picture_path;
            $news_sub_topic_id = $approval_ids->sub_topic_id;
            $user_id = $approval_ids->user_id;
            try {
                $data["news_title"] = $news_new;
                $data["news_content"] = $news_content;
                $data["news_slug"] = $news_slug;
                $data["news_picture_link"] = $news_picture_link;
                $data["news_picture_name"] = $news_picture_name;
                $data["news_picture_path"] = $news_picture_path;
                $data["added_at"] = $date_now;
                $data["news_status"] = "Paid";
                $data["sub_topic_id"] = $news_sub_topic_id;
                $data["user_id"] = $user_id;
                $user = User::findOrFail($user_id);
                $email_data = [
                    "greeting" => "Creating Author Account Approval",
                    "messages" => "Your request to create news titled " . $approval_ids->news_title
                        . " was approved, " . $user->name . "." . " You got IDR.10000,00",
                    "actionText" => "You can create news again by clicking below button",
                    "actionURL" => "http://localhost:3006/",
                    "thanks" => "Thank you for your participation",
                ];
                $user->notify(new EmailNewsApprovalNotification($email_data));
                $delete_news_approval = AdminNewsApproval::find($approval_id);
                $delete_news_approval->delete();
                $created_news = News::create($data);
                DB::table('users')
                    ->where('id', $approval_ids->user_id)
                    ->update([
                        'balance' => DB::raw('balance + 10000.00')
                    ]);
                $response = response()->json(["approved_news" => $created_news, "status" => "Succes", "status_code" => 200], 200);
            } catch (Exception $excption) {
                $response = response()->json(["status" => "Failed", $excption->getMessage()]);
            }
        }
        return $response;
    }
    public function reject(int $approval_id)
    {
        $delete_admin_approval = AdminNewsApproval::findOrFail($approval_id);
        $user = User::findOrFail($delete_admin_approval->user_id);
        $admin_approval_news = AdminNewsApproval::findOrFail($approval_id)
            ->select("id", "news_picture_name", "news_picture_path")->first();
        $email_data = [
            "greeting" => "Creating Author Account Approval",
            "messages" => "Your request to create news titled " . $delete_admin_approval->news_title . " was rejected, " . $user->name,
            "actionText" => "You can create news again by clicking this button",
            "actionURL" => "http://localhost:3006/",
            "thanks" => "Thank you for your participation",
        ];
        $user->notify(new EmailNewsApprovalNotification($email_data));
        $delete_admin_approval->delete();
        if (File::exists($admin_approval_news->news_picture_path . "/" . $admin_approval_news->news_picture_name)) {
            File::delete($admin_approval_news->news_picture_path . "/" . $admin_approval_news->news_picture_name);
        }
        return response()->json(["admin_approval" => $delete_admin_approval, "status" => "Success", "status_code" => 200, "You have rejected to create an author account"], 200);
    }
    public function getTopicIdByTopicSlug(string $topic_slug)
    {
        $string_topic_slug = strval($topic_slug);
        $id = Topics::where("topic_slug", $string_topic_slug)->select("id")->get();
        $json_encode = json_decode($id);
        return response(["id" => $json_encode[0]->id, "status" => "success"], 200);
    }
    public function getSubTopicIdBySubTopicSlug(string $sub_topic_slug)
    {
        $string_sub_topic_slug = strval($sub_topic_slug);
        $id = SubTopics::where("sub_topic_slug", $string_sub_topic_slug)->select("id")->get();
        $json_encode = json_decode($id);
        return $json_encode[0]->id;
    }
    public function showNewsByTopics()
    {
        DB::enableQueryLog();
        $news = Topics::with(["news" => function ($query) {
            $query->select("news.*", "sub_topics.*");
        }])->get();
        return response()->json($news, 200);
    }
    // For home page in topic_home, visitor
    public function showNewsByOneTopic($topic_slug)
    {
        DB::enableQueryLog();
        $news = Topics::with(["news" => function ($query) {
            $query->select("news.*", "sub_topics.*");
        }])->where("topic_slug", $topic_slug)->get();
        return response()->json($news, 200);
    }
    public function showNewsBySubTopicsAndTopics(string $sub_topic_slug)
    {
        DB::enableQueryLog();
        $sub_topic = SubTopics::where("sub_topic_slug", $sub_topic_slug)->first();
        $join_news = News::join("sub_topics", "sub_topics.id", "=", "news.sub_topic_id")
            ->select(
                "news.*",
                "sub_topics.sub_topic_title",
                "sub_topics.added_at as sub_topic_added_at",
                "sub_topics.updated_at as sub_topic_updated_at"
            )
            ->where("news.sub_topic_id", intval($sub_topic->id))
            ->get();
        return response()->json($join_news, 200);
    }
    public function showNewsByUserId(int $id)
    {
        DB::enableQueryLog();
        $join_news = DB::table("news")->join("sub_topics", "sub_topics.id", "=", "news.sub_topic_id")
            ->join("users", "users.id", "=", "news.user_id")
            ->select(
                "users.id",
                "users.name",
                "users.role",
                "news.id",
                "news.news_title",
                "news.news_content",
                "news.news_slug",
                "news.news_picture_link",
                "news.news_picture_name",
                "news.news_picture_path",
                "news.added_at as news_added_at",
                "news.updated_at as news_updated_at",
                "sub_topics.sub_topic_title",
                "sub_topics.added_at as sub_topic_added_at",
                "sub_topics.updated_at as sub_topic_updated_at",
                "sub_topics.topic_id"
            )
            ->where("user_id", $id)
            ->get();
        $decode = json_decode($join_news);
        $topic = Topics::where("id", $join_news->value("topic_id"));
        return response()->json(["news" => $join_news, "topics" => $topic], 200);
    }
    public function readingNews(string $news_slug)
    {
        DB::enableQueryLog();
        $join_news = DB::table("news")
            ->join("sub_topics", "sub_topics.id", "=", "news.sub_topic_id")
            ->join("users", "users.id", "=", "news.user_id")
            ->select(
                "news.*",
                "sub_topics.id as sub_topic_id",
                "sub_topics.sub_topic_title",
                "sub_topics.sub_topic_slug",
                "sub_topics.topic_id",
                "users.name",
                "users.photo_profile_link",
                "users.photo_profile_name",
                "sub_topics.added_at as sub_topic_added_at",
                "sub_topics.updated_at as sub_topic_updated_at"
            )
            ->where("news.news_slug", $news_slug)
            ->first();
        $topic = Topics::find($join_news->topic_id);
        return response()->json(["news" => $join_news, "topics" => $topic], 200);
    }
    public function showRelatedNews($news_slug)
    {
        $relatedNews = DB::select(DB::raw("SELECT news.*,`sub_topics`.`id`,`sub_topics`.`sub_topic_title`,`sub_topics`.`sub_topic_slug`, `topics`.`topic_title`,`topics`.`topic_slug` FROM `news`
        INNER JOIN `sub_topics` ON `sub_topics`.`id` = `news`.`sub_topic_id`
        INNER JOIN `topics` ON  `topics`.id = `sub_topics`.`topic_id` WHERE NOT  `news_slug` = :news_slug"), [strval($news_slug)]);
        return response()->json($relatedNews, 200);
        // $relateNews = Topics::with("news")->whereNot(function ($query) {
        //     $query->select("news.*", "topics.*", "sub_topics.*")->where("sub_topics",$sub_topic_slug);
        // })->get();
    }
    public function openNewsPicture($news_id)
    {
        DB::enableQueryLog();
        $news = News::find(intval($news_id));
        $header = array(
            header("Content-Type: " . File::mimeType($news->news_picture_path . "/" . $news->news_picture_name)),
            header(
                "Content-Length: " . File::size($news->news_picture_path . "/" . $news->news_picture_name),
            ),
            header("Access-Control-Allow-Origin: *")
        );
        return readfile($news->news_picture_path . "/" . $news->news_picture_name);
    }
    public function updateNews(Request $request, int $news_id)
    {
        $news = News::where("id", $news_id)->get();
        $update_news = News::findOrFail($news_id);
        $news_title = ucwords($request->news_title);
        $news_content = $request->news_content;
        $sub_topic_id = $request->sub_topic_id;
        if ($request->file("image_file")->getClientOriginalName() != null) {
            $file_image = $request->file("image_file");
            $directory = "storage";
            $file_name = $file_image->getClientOriginalName();
            $path_info = pathinfo($file_name);
            $base_name = $path_info["filename"];
            $counter = 1;
            $extension_test = File::extension($file_name);
            $current_counter_file = $base_name . "_" . $counter  . "." . $extension_test;
            if (File::exists($news->value("news_picture_path") . "/" . $news->value("news_picture_name"))) {
                // File::delete($news->value("news_picture_path") . "/" . $news->value("news_picture_name"));
                die();
            }
            if (File::exists($directory . "/" . "news_image/" . $file_name)) {
                if (File::exists($directory . "/" . "news_image/" . $current_counter_file)) {
                    do {
                        $next_counter_file = $base_name . "_" . $counter  . "." . $extension_test;
                        $current_counter_file = $next_counter_file;
                        $counter++;
                    } while (File::exists($directory . "/" . "news_image/" . $current_counter_file));
                    $file_name =  $current_counter_file;
                } else {
                    $file_name = $current_counter_file;
                }
            } else {
                $file_name = $file_image->getClientOriginalName();
            }
            $image_url_directory = stripslashes($request->schemeAndHttpHost() . "/" . $directory . "/news_image" . "/" . $file_name);
            File::move($file_image, $directory . "/" . "news_image/" . $file_name);
            // $request->file('file_image')->move($directory . "/" . "news_image/", $file_name);
            dd('asd');
            $data["news_title"] = $news_title;
            $data["news_content"] = $news_content ?? '-';
            $data["news_slug"] = preg_replace("/\s+/", "-", strtolower($news_title)) . '-' . $this->random_string();
            $data["news_picture_link"] = $image_url_directory;
            $data["news_picture_name"] = $file_name;
            $news_data["news_picture_path"] = preg_replace("/\s+/", "", strtolower("storage/news_image"));
            $data['sub_topic_id'] = $sub_topic_id;
            $updated_at = round(microtime(true) * 1000); // GOBLOOOOOKKKKKKKKKKKKKKKKKKKKKK
            $data["updated_at"] = $updated_at;
            // $update_news->fill($data);
            News::findOrFail($news_id)->update($data);
            return response()->json(["news" => $data, "status_code" => 200], 200);
        } else {
            $data["news_title"] = $news_title;
            $data["news_content"] = $news_content ?? '-';
            $data["news_slug"] = preg_replace("/\s+/", "-", strtolower($news_title)) . '-' . $this->random_string();
            $updated_at = round(microtime(true) * 1000); // GOBLOOOOOKKKKKKKKKKKKKKKKKKKKKK
            $data["updated_at"] = $updated_at;
            $data['sub_topic_id'] = $sub_topic_id;
            News::findOrFail($news_id)->update($data);
        }
    }

    public function delete(int $news_id)
    {
        $news = News::findOrFail($news_id);
        if (File::exists("storage/news_image/" . $news->news_picture_name)) {
            File::delete("storage/news_image/" . $news->news_picture_name);
        }
        $news->delete();
        // return response(["sub_topics" => $news, "status" => "Successfully", "status_code" => 200, "message" => "Successfully delete news"], 200);


    }
}
