<?php

namespace App\Http\Controllers;

use App\Models\chats;
use App\Models\Conversations;
use Inertia\Inertia;
use App\Models\Article;
use App\Models\Boutique;
use App\Models\ArticleMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function myArticles()
    {
        $user = Auth::user();
        $prefs = json_decode($user->preferences);
        $articles = Article::whereIn('category', $prefs)->with('boutique')->with('articleMedia')->orderBy('created_at', 'desc')->limit(10)->get();
        return $articles;
    }
    public function allArticles()
    {
        $articles = Article::with('articleMedia')->with('boutique')->orderBy('commande', 'desc')->limit(10)->get();
        return $articles;
    }
    public function moreD($num)
    {
        $articles = Article::with('articleMedia')->with('boutique')->orderBy('commande', 'desc')->limit($num)->get();
        return $articles;
    }

    public function moreA($num){
        $user = Auth::user();
        $prefs = json_decode($user->preferences);
        $articles = Article::whereIn('category', $prefs)->with('boutique')->with('articleMedia')->orderBy('created_at', 'desc')->limit($num)->get();
        return $articles;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('article/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $imagesTab = [];
        // dd($request);
        $letterTab = ['a','b','c','d','e','f'];
        if ($request->hasFile('image')) {
            $imageName = time() . $letterTab[0]. '.' . $request->image->extension();
            $request->image->move(public_path('article_medias'), $imageName);
            $imagePrincipal = ArticleMedia::create([
                'path' => $imageName,
            ]);
        }
        if ($request->hasFile('image0')) {
            for ($i = 0; $i < count($request->files)-1; $i++) {
                $otherImageName = time() . $letterTab[$i+1]. '.' . $request['image' . $i]->extension();
                $request['image' . $i]->move(public_path('article_medias'), $otherImageName);
                $image = ArticleMedia::create([
                    'path' => $otherImageName,
                ]);
                array_push( $imagesTab, $image->id);
            }
        }
        count($imagesTab) > 0 ? $imagesTab = json_encode( $imagesTab ) : null;
        $boutique = Boutique::where('user_id', Auth::user()->id)->get()[0];
        // dd(public_path('boutique_medias').$imageName);
        $boutique = Article::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'images' => $imagesTab,
            'commande' => 0,
            'boutique_id' => $boutique->id,
            'article_media_id' => $imagePrincipal ? $imagePrincipal->id : null,
        ]);

        return json_encode('done');
    }


    public function showArticle($id){
        $article = Article::with('boutique')->where('id', $id)->get()[0];
        $boutique = Boutique::find($article->boutique_id);
        $imageIds = json_decode($article->images);
        $images = ArticleMedia::whereIn('id', $imageIds)->get();
        $image = ArticleMedia::find($article->article_media_id);
        $articles = Article::where('category', $article->category)->where('id', '!=', $id)->orderBy('commande', 'desc')->with('boutique')->with('articleMedia')->limit(6)->get();
        // dd($articles);
        return Inertia::render('article/Visit', [
            'article' => $article,
            'image' => $image,
            'images' => $images,
            'articles' => $articles,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $boutique = Boutique::find($article->boutique_id);
        if (Auth::user()->id === $boutique->user_id) {
            $proprio = true;
        } else {
            $proprio = false;
        }
        $imageIds = json_decode($article->images);
        $images = ArticleMedia::whereIn('id', $imageIds)->get();
        $image = ArticleMedia::find($article->article_media_id);
        return Inertia::render('article/Show', [
            'article' => $article,
            'image' => $image,
            'proprio' => $proprio,
            'images' => $images,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->files);
        $article = Article::find($request->id);
        $images = json_decode($request->images);
        $imagePrincipal = null;
        $letterTab = ['a','b','c','d','e','f'];
        if ($request->hasFile('image')) {
            $imageName = time() . $letterTab[0]. '.' . $request->image->extension();
            $request->image->move(public_path('article_medias'), $imageName);
            $imagePrincipal = ArticleMedia::create([
                'path' => $imageName,
            ]);
        }
        if (($request->hasFile('image') && count($request->files) > 1) || (!$request->hasFile('image') && count($request->files) > 0)) {
            if ($request->hasFile('image0')) {
                $otherImageName = time() . $letterTab[0]. '.' . $request->image0->extension();
                $request->image0->move(public_path('article_medias'), $otherImageName);
                $image = ArticleMedia::create([
                    'path' => $otherImageName,
                ]);
                $images[0] = $image->id;
            }
        }
        if (($request->hasFile('image') && count($request->files) > 1) || (!$request->hasFile('image') && count($request->files) > 0)) {
            if ($request->hasFile('image1')) {
                $otherImageName = time() . $letterTab[1]. '.' . $request->image1->extension();
                $request->image1->move(public_path('article_medias'), $otherImageName);
                $image = ArticleMedia::create([
                    'path' => $otherImageName,
                ]);
                $images[1] = $image->id;
            }
        }
        if (($request->hasFile('image') && count($request->files) > 1) || (!$request->hasFile('image') && count($request->files) > 0)) {
            if ($request->hasFile('image2')) {
                $otherImageName = time() . $letterTab[2]. '.' . $request->image2->extension();
                $request->image2->move(public_path('article_medias'), $otherImageName);
                $image = ArticleMedia::create([
                    'path' => $otherImageName,
                ]);
                $images[2] = $image->id;
            }
        }
        if (($request->hasFile('image') && count($request->files) > 1) || (!$request->hasFile('image') && count($request->files) > 0)) {
            if ($request->hasFile('image3')) {
                $otherImageName = time() . $letterTab[3]. '.' . $request->image3->extension();
                $request->image3->move(public_path('article_medias'), $otherImageName);
                $image = ArticleMedia::create([
                    'path' => $otherImageName,
                ]);
                $images[3] = $image->id;
            }
        }
        if (($request->hasFile('image') && count($request->files) > 1) || (!$request->hasFile('image') && count($request->files) > 0)) {
            if ($request->hasFile('image4')) {
                $otherImageName = time() . $letterTab[4]. '.' . $request->image4->extension();
                $request->image4->move(public_path('article_medias'), $otherImageName);
                $image = ArticleMedia::create([
                    'path' => $otherImageName,
                ]);
                $images[4] = $image->id;
            }
        }
        $article->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'article_media_id' => $imagePrincipal ? $imagePrincipal->id : $article->article_media_id,
            'images' => json_encode($images),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }

    public function commande(Request $request)
    {
        $article = Article::find($request->id);
        $article->update([
            'commande' => $article->commande + 1
        ]);
        $boutique = Boutique::find($article->boutique_id);
        $boutique->update([
            'commande' => $boutique->commande + 1
        ]);
        // $conversation = Conversations::where('initiator', Auth::user()->id)->where('receiver', $article->boutique_id)->get();
        // $message = "Je suis vraiment intéressé par votre article " . $article->name;
        // if (count($conversation) === 0) {
        //     $conversation = Conversations::create([
        //         'initiator' => Auth::user()->id,
        //         'receiver' => $article->boutique_id,
        //         'toBoutique' => true,
        //     ]);
        //     $chat = chats::create([
        //         'message' => $message,
        //         'sender' => Auth::user()->id,
        //         'receiver' => $boutique->id,
        //         'media_id' => null,
        //         'conv_id' => $conversation->id,
        //     ]);
        // } else {
        //     $conversation = $conversation[0];
        //     $chat = chats::create([
        //         'message' => $message,
        //         'sender' => Auth::user()->id,
        //         'receiver' => $boutique->id,
        //         'media_id' => null,
        //         'conv_id' => $conversation->id,
        //     ]);
        // }
        // return $conversation;
    }

}
