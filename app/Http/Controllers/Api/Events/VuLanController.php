<?php

namespace App\Http\Controllers\Api\Events;

use App\Http\Controllers\Controller;
use App\Repositories\Vulan\VulanRepository;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\VuLanTemplate;
use App\Models\VuLanHistory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class VuLanController extends Controller
{
    /**
     * @var VulanRepository
     */
    protected $repo;
    protected $msg;
    protected $helper;

    public function __construct(
        VulanRepository $repo,
        Message         $message,
        Helpers         $helper,
        VuLanHistory $model
    )
    {
        $this->repo = $repo;
        $this->msg = $message;
        $this->helper = $helper;
        $this->repo->addModel($model);
    }

    public function templateList(): \Illuminate\Http\JsonResponse
    {
        try {
            $templates = VuLanTemplate::all();
            $lists = $this->repo->getAll();
            $results = array(
                'success' => true,
                'data' => $lists,
                'templates'=>$templates->toArray(),
                'message' => $this->msg->getSuccess(),
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json($results);

        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json([$results], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function templateDetail(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->repo->getById($id);
            $results = array(
                'success' => true,
                'data' => $data,
                'message' => $this->msg->getSuccess(),
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json($results);

        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json([$results], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function userSave(Request $request)
    {
        try {
            $userFiles = [];
            foreach ($request->all() as $key => $file) {
                if ($request->hasFile($key)) {
                    $userFiles[] = $this->helper->upLoadVuLanFiles($file);
                }
            }

            // TODO: check and remove old file if user update new data

            $results = array(
                'success' => true,
                'data' => $userFiles,
                'message' => $this->msg->getSuccess(),
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json($results);

        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json([$results], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function uploadPreviewVideo(Request $request)
    {
        try {
            $videoPath = $this->helper->upLoadVuLanPreviewVideo($request->file("preview_video"));

            $results = array(
                'success' => true,
                'data' => $videoPath,
                'message' => $this->msg->getSuccess(),
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json($results);

        } catch (\Throwable $th) {
            $results = array(
                'message' => $th->getMessage(),
                'success' => false,
                'status' => ResponseAlias::HTTP_OK
            );
            return response()->json([$results], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}