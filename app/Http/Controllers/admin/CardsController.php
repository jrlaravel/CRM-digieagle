<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Assign_card;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CardsController extends Controller
{
    public function index()
    {
        $data = Card::all();  
       
        return view('admin/cards',compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' =>'required|max:255',
            'description' =>'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if this is an update
        if ($request->card_id) {
            // Update existing card
            $card = Card::find($request->card_id);
                if ($card) {
                    $card->name = $request->name;
                    $card->description = $request->description;
                    if ($request->hasFile('image')) {
                        // Delete old image from local folder
                        if (file_exists(public_path('storage/cards/'.$card->image))) {
                            unlink(public_path('storage/cards/'.$card->image));
                        }
                        // Upload new image
                        $image = $request->file('image');
                        $imageName = time().'.'.$image->getClientOriginalExtension();
                        $image->move(public_path('storage/cards'), $imageName);
                        $card->image = $imageName;
                    }
                    $card->save();
                    return back()->with('success', 'Card updated successfully.');
                }
            } 
            else {
                // Create new card
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('storage/cards'), $imageName);

                Card::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'image' => $imageName,
                ]);
                return back()->with('success', 'Card added successfully.');
        }
    }


    public function delete($id)
    {
        $card = Card::find($id);
        if ($card) {
            $imagePath = public_path('storage/cards/' . $card->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $card->delete();
        }

        return redirect()->route('admin/cards')->with('success', 'Card deleted successfully.');
    }

    public function assign_card()
    {
        $data = DB::select('select first_name,last_name,id from users where role = '.'"employee"');
        $card = DB::select('select id,name from card');
        $assigncard = DB::select('SELECT first_name,last_name,assign_card.id,assign_card.date,card.name,assign_card.message FROM `assign_card` join card on assign_card.card_id = card.id join users on assign_card.user_id = users.id');
        return view('admin/assign-card',compact('data','card','assigncard'));
    }

    public function assign_card_store(Request $request)
    {
        // return $request->all();
        Assign_card::create([
            'card_id' => $request->card,
            'user_id' => $request->employee,
            'message' => $request->message,
            'date' => Carbon::now()
        ]);
        return back()->with('success', 'Card assigned successfully.');
    }

    public function assign_card_delete($id)
    {
        $data = Assign_card::find($id);
        $data->delete();

        return back()->with('success', 'Card unassigned successfully.');
    }
}
