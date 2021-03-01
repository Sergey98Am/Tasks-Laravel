<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardRequest;
use App\Models\Board;
use JWTAuth;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $boards = Board::where('user_id', JWTAuth::user()->id)
                ->where('deleted_at', NULL)
                ->orderBy('id', 'DESC')
                ->get();

            return response()->json([
                'boards' => $boards
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BoardRequest $request)
    {
        try {
            $board = Board::create([
                'title' => $request->title,
                'user_id' => JWTAuth::user()->id
            ]);

            if (!$board) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                'board' => $board,
                'message' => 'Board successfully created'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BoardRequest $request, $id)
    {
        try {
            $board = Board::find($id);

            if (!$board) {
                throw new \Exception('Board does not exist');
            }

            $board->update([
                'title' => $request->title,
                'user_id' => JWTAuth::user()->id
            ]);

            return response()->json([
                'board' => $board,
                'message' => 'Board successfully updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $board = Board::find($id);

            if (!$board) {
                throw new \Exception('Board does not exist');
            }

            $board->delete();

            return response()->json([
                'board' => $board,
                'message' => 'Board successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
