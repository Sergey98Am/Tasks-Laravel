<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Card;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;

class CommentController extends Controller
{
    public function index($cardId)
    {
        try {
            $card = Card::with([
                'comments' => function ($q) {
                    $q->with([
                        'replies' => function ($q) {
                            $q->with('user');
                        },
                        'user',
                    ])->orderBy('id', 'DESC');
                }
            ])->find($cardId);
            return response()->json([
                'comments' => $card->comments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function store(CommentRequest $request, $cardId)
    {
        try {
            $comment = Card::find($cardId)->comments()->create([
                'comment' => $request->comment,
                'card_id' => $cardId,
                'user_id' => JWTAuth::user()->id
            ]);

            if (!$comment) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                'comment' => $comment->load('user'),
                'message' => 'Comment successfully created'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function reply(CommentRequest $request, $cardId, $commentId)
    {
        try {
            $comment = Comment::find($commentId)->create([
                'comment' => $request->comment,
                'parent_id' => $commentId,
                'card_id' => $cardId,
                'user_id' => JWTAuth::user()->id,
            ]);

            if (!$comment) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                'comment' => $comment->load('user'),
                'message' => 'Comment successfully created'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, $cardId, $commentId)
    {
        try {
            $comment = Comment::find($commentId);

            if (!$comment) {
                throw new \Exception('Comment does not exist');
            }

            if ($request->comment == '') {
                $request->comment = $comment->comment;
            }

            $comment->update([
                'comment' => $request->comment,
            ]);

            return response()->json([
                'comment' => $comment,
                'message' => 'Comment successfully updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($cardId, $commentId)
    {
        try {
            $comment = Comment::find($commentId);

            if (!$comment) {
                throw new \Exception('Comment does not exist');
            }

            $comment->delete();

            return response()->json([
                'comment' => $comment,
                'message' => 'Comment successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
