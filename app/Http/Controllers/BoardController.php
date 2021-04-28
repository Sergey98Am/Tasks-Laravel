<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardRequest;
use App\Models\Board;
use App\Models\User;
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
            $user_id = JWTAuth::user()->id;
            $user = User::with('boards')->find($user_id);
            return response()->json([
                'boards' => $user->boards
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
            ]);

            $board->users()->attach(JWTAuth::user()->id);

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
     * @param $boardId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BoardRequest $request, $boardId)
    {
        try {
            $board = Board::find($boardId);

            if (!$board) {
                throw new \Exception('Board does not exist');
            }

            $board->update([
                'title' => $request->title,
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
     * @param $boardId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($boardId)
    {
        try {
            $board = Board::find($boardId);

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

    public function singleBoard($boardId)
    {
        try {
            $board = Board::with([
                'lists' => function ($q) {
                    $q->orderBy('order')->with([
                        'cards' => function ($q) {
                            $q->orderBy('order');
                        }
                    ]);
                }
            ])->with('users')->find($boardId);

            return response()->json([
                'board' => $board
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
