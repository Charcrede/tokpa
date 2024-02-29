<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\BoutiqueMedia;
use Inertia\Inertia;
use App\Models\chats;
use Illuminate\Http\Request;
use App\Models\Conversations;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{

    public function showChats($id){
        $allMessages = chats::where('conv_id',$id)->get();
        $conversation = Conversations::find($id);
        $logo = '';
        if ($conversation->toBoutique == 1) {
            $boutique = Boutique::find($conversation->receiver);
            $logo = BoutiqueMedia::find($boutique->logo);
            $user = '';
        }else{
            if ($conversation->receiver === Auth::user()->id) {
                $user = User::find($conversation->initiator);
                $boutique = '';
            }else{
                $user = User::find($conversation->receiver);
                $boutique = '';
            }
        }
        return Inertia::render('chats/Discussion', [
            'convers' => $allMessages,
            'user' => $user,
            'boutique' => $boutique,
            'logo' => $logo,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function sendChat(Request $request)
    {
        $conv = Conversations::find($request->conv_id);
        if ($conv->toBoutique == '1') {
            $message = chats::create([
                'message' => $request->msg,
                'sender' => $conv->initiator,
                'receiver' => $conv->receiver,
                'media_id' => null,
                'conv_id'=> $conv->id
            ]);
            $allMessages = chats::where('conv_id',$conv->id)->get();
            return $allMessages;
        }else{
            if ($conv->initiator === Auth::user()->id) {
                $message = chats::create([
                    'message' => $request->msg,
                    'sender' => $conv->initiator,
                    'receiver' => $conv->receiver,
                    'media_id' => null,
                    'conv_id'=> $conv->id
                ]);
                $allMessages = chats::where('conv_id',$conv->id)->get();
                return $allMessages;
            }else{
                $message = chats::create([
                    'message' => $request->msg,
                    'sender' => $conv->receiver,
                    'receiver' => $conv->initiator,
                    'media_id' => null,
                    'conv_id'=> $conv->id
                ]);
                $allMessages = chats::where('conv_id',$conv->id)->get();
                return $allMessages;
            }
        }
    }

    public function getBoutiqueConvs($id){
        $convs = Conversations::where('toBoutique', '1')->where('receiver', $id)->get();
        // dd($convs);
        // $messages = chats::orderBy('created_at','desc')->whereIn('conv_id', $convs)->limit(1)->get();

        $usersMessages = [];

        for ($i=0; $i < count($convs); $i++) { 
            $user = User::select('firstname', 'lastname')->where('id', $convs[$i]->initiator)->get()[0];
           $messages = chats::orderBy('created_at','desc')->where('conv_id', $convs[$i]->id)->limit(1)->get();
            array_push($usersMessages, ['user' => $user, 'message' =>$messages[0]]);
        }
        // dd($messages);
        return Inertia::render('chats/Index', [
            "messages" => $usersMessages
        ]);
    }
    public function showChatsBoutique($id){

        $allMessages = chats::where('conv_id',$id)->get();
        $conversation = Conversations::find($id);
        $logo = '';
        $user = User::find($conversation->initiator);
        $boutique = '';
        return Inertia::render('chats/Discussion', [
            'convers' => $allMessages,
            'user' => $user,
            'boutique' => $boutique,
            'logo' => $logo,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(chats $chats)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function boutiqueSend(Request $request){
        $conv = Conversations::find($request->conv_id);
        $message = chats::create([
            'message' => $request->msg,
            'sender' => $conv->receiver,
            'receiver' => $conv->initiator,
            'media_id' => null,
            'conv_id'=> $conv->id
        ]);
        $allMessages = chats::where('conv_id',$conv->id)->get();
        return $allMessages;
    }
}