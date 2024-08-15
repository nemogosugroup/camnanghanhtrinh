<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\VuLanHistory;
use App\Repositories\Vulan\VulanHistoryRepository;
use App\Repositories\Vulan\VulanRepository;
use App\Helpers\Message;
use App\Helpers\Helpers;
use App\Models\VuLanTemplate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class VuLanTemplatesController extends Controller
{
    /**
     * @var VulanRepository
     */
    protected VulanRepository $repo;
    protected VulanHistoryRepository $historyRepo;
    protected Message $msg;
    protected Helpers $helper;

    public function __construct(
        VulanRepository $repo,
        VulanHistoryRepository $historyRepo,
        Message         $message,
        Helpers         $helper,
        VuLanTemplate $model,
        VuLanHistory $historyModel
    )
    {
        $this->repo = $repo;
        $this->repo->addModel($model);
        $this->historyRepo = $historyRepo;
        $this->historyRepo->addModel($historyModel);
        $this->msg = $message;
        $this->helper = $helper;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $lists = $this->repo->getAll();
            $results = array(
                'success' => true,
                'data' => $lists,
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

    public function detail(int $id): \Illuminate\Http\JsonResponse
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

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {dd($request->all());
        try {
            $filesData = [];
            $files = $request->file('files');
            $types = $request->input('files');
            foreach ($files as $idx => $data) {
                $filesData[$idx]['url'] = $this->helper->upLoadVuLanFiles($data['file']);
                $filesData[$idx]['type'] = $types[$idx]['type'];
                $filesData[$idx]['show_content'] = $types[$idx]['show_content'];
            }

            $content = $this->historyRepo->convertContent($request->input("template_id"), $request->input("content"), $filesData);

            $this->historyRepo->create([
                "template_id" => $request->input("template_id"),
                "history_id" => $request->input("history_id"),
                "content" => json_encode($content),
            ]);

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

    public function uploadPreviewVideo(Request $request): \Illuminate\Http\JsonResponse
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
