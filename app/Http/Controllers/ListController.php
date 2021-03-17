<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ListRequest;
use App\Models\Board;
use App\Models\Lists;

class ListController extends Controller
{
    public function index($boardId)
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
            ])->find($boardId);

            return response()->json([
                'board' => $board
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function store(ListRequest $request, $boardId)
    {
        try {
            $list = Board::find($boardId)->lists()->create([
                'title' => $request->title,
                'board_id' => $boardId
            ])->load('cards');

//            $a = Lists::with('cards')->find($list->id);

            if (!$list) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                'list' => $list,
                'message' => 'List successfully created'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, $boardId, $listId)
    {
        try {
            $list = Board::find($boardId)->lists()->find($listId);

            if (!$list) {
                throw new \Exception('List does not exist');
            }

            if ($request->title == '') {
                $request->title = $list->title;
            }

            $list->update([
                'title' => $request->title,
            ]);

            return response()->json([
                'list' => $list,
                'message' => 'List successfully updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($boardId, $listId)
    {
        try {
            $list = Board::find($boardId)->lists()->find($listId);

            if (!$list) {
                throw new \Exception('List does not exist');
            }

            $list->delete();

            return response()->json([
                'list' => $list,
                'message' => 'List successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function sortList(Request $request)
    {
        try {
            $position = 0;
            foreach ($request->ids as $id) {
                Lists::where('id', $id)->update(['order' => $position]);
                $position++;
            }
        }  catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
