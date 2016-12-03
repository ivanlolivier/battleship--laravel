<?php

namespace App\Http\Controllers;

use App\Board;
use App\Http\Requests\SetShipRequest;
use App\Http\Requests\ShotRequest;
use Illuminate\Http\Request;

class GameController extends Controller
{

    /******************
     * VIEWS
     */

    public function board($user_id, Request $request)
    {
        if (!in_array($user_id, [1, 2])) {
            return redirect('/');
        }

        $board = $request->session()->get('Board' . $user_id, new Board());
//        $board = new Board();
//        session(['Board' . $user_id => $board]);

        return view('board.show', compact('board', 'user_id'));
    }

    public function restart(Request $request)
    {
        $request->session()->forget('Board 1');
        $request->session()->forget('Board 2');

        return redirect('/');
    }

    /******************
     * API
     */

    public function addShip($user_id, SetShipRequest $request)
    {
        /** @var Board $board */
        $board = $request->session()->get('Board' . $user_id, new Board());

        $board->setShip(
            $request->get('ship_name'),
            $request->get('row'),
            $request->get('col'),
            $request->get('orientation')
        );

        session(['Board' . $user_id => $board]);

        return response()->json([]);
    }

    public function shot($user_id, ShotRequest $request)
    {
        $board = $request->session()->get('Board' . $user_id, new Board());

        $result = $board->shot($request->get('row'), $request->get('col'));

        $message = 'miss';
        if ($result == 0) {
            $message = "hit";
        } elseif ($result == 1) {
            $message = "You sank my battleship!";
        }

        return response()->json([
            'result'  => $result,
            'message' => $message
        ]);
    }


}
