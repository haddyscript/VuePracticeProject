<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageDetails;
use Illuminate\Support\Facades\Log;

class PageDetailController extends Controller
{


    public function getPage(Request $request)
    {
        $page = $request->input('page'); 

        $page2 = (int) ($request->input('paginate') ?? 1);
        $limit_per_page = 10;

        $pageDetails = PageDetails::where('page', $page)
        ->orderBy('created_at', 'asc')
        ->paginate($limit_per_page, ['*'], 'mypage', $page2);

        if ($pageDetails->isEmpty()) {
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page details not found!',
                'pagination' => [
                    'current_page' => $pageDetails->currentPage(),
                    'last_page' => $pageDetails->lastPage(),
                    'per_page' => $pageDetails->perPage(),
                    'total' => $pageDetails->total(),
                ],
            ]);
        }
        return response()->json([
            'success' => 'true',
            'status' => 'success',
            'message' => 'Page details found!',
            'page' => $pageDetails->items(),
            'pagination' => [
                'current_page' => $pageDetails->currentPage(),
                'last_page' => $pageDetails->lastPage(),
                'per_page' => $pageDetails->perPage(),
                'total' => $pageDetails->total(),
            ],
        ]);
    }

    public function deletePageDetail(Request $request) {

        $page_id = $request->input('data_id');

        $page = PageDetails::where('id', $page_id)->delete();

        if (!$page) {
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page details cannot be deleted, please try again!',
            ]);
        }

        return response()->json([
            'success' => 'true',
            'status' => 'success',
            'message' => 'Page details deleted successfully!',
            'page' => $request->all()
        ]);
    }

    public function getCertainPagesDetailToEdit(Request $request){

        $page_id = $request->input('item'); 

        $pageDetails = PageDetails::where('id', $page_id)->first();

        if (!$pageDetails) {
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page details not found!',
                'page' => $request->all()
            ]);
        }

        return response()->json([
            'success' => 'true',
            'status' => 'success',
            'message' => 'Page details found!',
            'page' => $pageDetails,
        ]);
    }

    public function updatePagedetails(Request $request){

        $isValid = $this->checkDetails($request);

        if ($isValid !== null) {
            return $isValid;
        }

        $page = PageDetails::where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if (!$page) {
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page details cannot be updated, please try again!',
            ]);
        }

        return response()->json([
            'success' => 'true',
            'status' => 'success',
            'message' => 'Page details updated successfully!',
            'page' => $request->all()
        ]);
    }


    public function addPagedetails(Request $request){
        
        $isValid = $this->checkDetails($request);

        if ($isValid !== null) {
            return $isValid;
        }

        $page = PageDetails::create([
            'page' => $request->page,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if (!$page) {
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page details not saved, please try again!',
            ]);
        }

        return response()->json([
            'success' => 'true',
            'status' => 'success',
            'message' => 'Page details saved successfully!',
            'page' => $page
        ]);
    }

    private function checkDetails($request){

        if( isset($request->page) && $request->page == null){
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page name must not be empty!',
                'page' => $request->page
            ]);
        }
        if( isset($request->title) && $request->title == null){
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page title must not be empty!',
                'title' => $request->title
            ]);
        }
        if( isset($request->description) && $request->description == null){
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Page description must not be empty!',
                'description' => $request->description
            ]);
        }

        return null;
    }



}