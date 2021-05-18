<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CardRequest;
use App\Models\Lists;
use App\Models\Card;

class CardController extends Controller
{
    public function store(CardRequest $request, $listId)
    {
        try {
            $card = Lists::find($listId)->cards()->create([
                'title' => $request->title,
                'lists_id' => $listId
            ]);

            if (!$card) {
                throw new \Exception('Something went wrong');
            }

            return response()->json([
                'card' => $card,
                'message' => 'Card successfully created'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, $listId, $cardId)
    {
        try {
            $card = Lists::find($listId)->cards()->find($cardId);

            if (!$card) {
                throw new \Exception('Card does not exist');
            }

            if ($request->title == '') {
                $request->title = $card->title;
            }

            $card->update([
                'title' => $request->title,
            ]);

            return response()->json([
                'card' => $card,
                'message' => 'Card successfully updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($listId, $cardId)
    {
        try {
            $card = Lists::find($listId)->cards()->find($cardId);

            if (!$card) {
                throw new \Exception('Card does not exist');
            }

            $card->delete();

            return response()->json([
                'card' => $card,
                'message' => 'Card successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function sortCard(Request $request)
    {
        try {
            $position = 0;
            foreach ($request->ids as $id) {
                Card::where('id', $id)->update(['order' => $position]);
                $position++;
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function moveCardToAnotherList(Request $request, $cardId)
    {
        try {
            $card = Card::find($cardId);

            if (!$card) {
                throw new \Exception('Card does not exist');
            }

            $card->update([
                'lists_id' => $request->lists_id,
            ]);

            return response()->json([
                'card' => $card,
                'message' => 'Card successfully moved'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function members($cardId)
    {
        try {
            $card = Card::with('members')->find($cardId);

            if (!$card) {
                throw new \Exception('Card does not exist');
            }

            return response()->json([
                'members' => $card->members
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function addOrRemoveMembers(Request $request, $cardId)
    {
        try {
            $card = Card::find($cardId);

            if (!$card) {
                throw new \Exception('Something went wrong');
            }

            $card->members()->sync($request->members);

            return response()->json([
                'card' => $card->load('members')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
