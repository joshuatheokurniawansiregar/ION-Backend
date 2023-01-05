<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminApproval;
use App\Models\User;
use Illuminate\Support\Facades\File;


class AuthorController extends Controller
{
    /**
     * First, get admin approval id by user id in admin approval table
     * Second, delete a record by admin approval id in admin approval table
     * Last, create a new athor account 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $admin_approval_user = AdminApproval::where("id", intval($id))->get();
        $json_decode_approval = json_decode($admin_approval_user);
        $id = $json_decode_approval[0]->id;
        $user_id_from_admin_approval = $json_decode_approval[0]->user_id;
        $user_photo_profile_path_from_admin_approval = $json_decode_approval[0]->photo_profile_path;
        $user_photo_profile_name_from_admin_approval = $json_decode_approval[0]->photo_profile_name;
        $user_author_description_from_admin_approval = $json_decode_approval[0]->author_description;
        $directory = "storage/photo_profile";
        $delete_admin_approval = AdminApproval::find($id);
        $create_author_role = User::find(intval($user_id_from_admin_approval));
        $data_author = array(
            "author_description" => $user_author_description_from_admin_approval,
            "role" => "author",
            "photo_profile_link" => $json_decode_approval[0]->photo_profile_link,
            "photo_profile_name" => $user_photo_profile_name_from_admin_approval,
            "photo_profile_path" => $user_photo_profile_path_from_admin_approval
        );
        try {
            $delete_admin_approval->delete();
            $create_author_role->update($data_author);
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json($th->getMessage(), 400);
        }
        return response()->json(["authors" => $json_decode_approval, "status" => "Success", "succes_code" => 200, "message" => "Author has created"], 200);
    }

    /**
     * First, get admin approval id by user id in admin approval table
     * Second, delete a record by admin approval id in admin approval table
     * Last, cancel to create a new athor account 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $admin_approval_user_id = AdminApproval::where("user_id", intval($id))
            ->select("id", "photo_profile_name", "photo_profile_path")->get();
        $json_decode_approval = json_decode($admin_approval_user_id);
        $user_id_json_decode_approval = $json_decode_approval[0]->id;
        $user_file_path_json_decode_approval = $json_decode_approval[0]->photo_profile_path;
        $user_file_name_json_decode_approval = $json_decode_approval[0]->photo_profile_name;
        $delete_admin_approval = AdminApproval::findOrFail($user_id_json_decode_approval);
        $delete_admin_approval->delete();
        if (File::exists($user_file_path_json_decode_approval . "/" . $user_file_name_json_decode_approval)) {
            File::delete($user_file_path_json_decode_approval . "/" . $user_file_name_json_decode_approval);
        }
        return response()->json(["admin_approval" => $delete_admin_approval, "status" => "Success", "status_code" => 200, "You have rejected to create an author account"], 200);
    }
}
