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

        return view('board.show', compact('board', 'user_id'));
    }

    public function restart(Request $request)
    {
//        $board1 = new Board();
//        session(['Board1' => $board1]);
        $request->session()->forget('Board1');

//        $board2 = new Board();
//        session(['Board2' => $board2]);
        $request->session()->forget('Board2');

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
        /** @var Board $board */
        $board = $request->session()->get('Board' . $user_id, new Board());

        $result = $board->shot($request->get('row'), $request->get('col'));

        $message = 'You missed';
        if ($result == Board::RESULT_HIT) {
            $message = "Yo make a hit";
        } elseif ($result == Board::RESULT_SUNKEN) {
            $message = "You sank a ship!";
        }

        return response()->json([
            'result'  => $result,
            'message' => $message
        ]);
    }


}
