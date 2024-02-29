<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Boutique;
use App\Models\BoutiqueMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BoutiqueController extends Controller
{


    public function plusRecent(Request $request){
        $articles = Article::with('articleMedia')->where('boutique_id', $request->boutique['id'])->orderBy('created_at', 'desc')->limit($request->num)->get();
        return $articles;
    }

    public function tendance(Request $request){
        $articles = Article::with('articleMedia')->where('boutique_id',$request->boutique['id'])->orderBy('commande', 'desc')->limit($request->num)->get();
        return $articles;
    }
    public function acceuil(){
        return Inertia::render('Accueil');
    }

    public function dashboard(){
        $user = Auth::user();
        $prefs = json_decode($user->preferences);
        $articles = Article::whereIn('category', $prefs)->with('boutique')->with('articleMedia')->orderBy('created_at', 'desc')->limit(10)->get();
        return Inertia::render('Dashboard', ['articles' => $articles]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boutique = Boutique::where('user_id', Auth::user()->id)->get();
        if (count($boutique)) {
            $boutique = $boutique[0];
            $logo = BoutiqueMedia::where('id', $boutique->logo)->get();
            $banner = BoutiqueMedia::where('id', $boutique->banner)->get();
            $articles = Article::with('articleMedia')->where('boutique_id', $boutique->id)->orderBy('created_at', 'desc')->limit(9)->get();
            $articlesCount = Article::where('boutique_id', $boutique->id)->count();
            return Inertia::render('boutique/Show', [
                'boutique' => $boutique,
                'logo' => $logo,
                'banner' => $banner,
                'propsArticles' => $articles,
                'articlesCount' => $articlesCount,
            ]);
        }else{
            return Inertia::render('boutique/Show',[
                'boutique' => ''
            ]);
        }
    }

    public function visit($name){
            $user = Auth::user();
            $boutique = Boutique::where('name', $name)->get()[0];
            if ($boutique->user_id === $user->id) {
                return $this->index();
            }
            $boutique->update([
                'visit' => $boutique->visit +1
            ]);
            $logo = BoutiqueMedia::where('id', $boutique->logo)->get();
            $banner = BoutiqueMedia::where('id', $boutique->banner)->get();
            $articles = Article::with('articleMedia')->where('boutique_id', $boutique->id)->orderBy('created_at', 'desc')->limit(9)->get();
            $articlesCount = Article::where('boutique_id', $boutique->id)->count();
            return Inertia::render('boutique/Visit', [
                'boutique' => $boutique,
                'logo' => $logo,
                'banner' => $banner,
                'propsArticles' => $articles,
                'articlesCount' => $articlesCount,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('boutique/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $logo = null;
        $banner = null;
        if($request->hasFile('logo')){
            $imageName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('boutique_medias'), $imageName);
            $pathLogo = $imageName;
            $logo = BoutiqueMedia::create([
                'path' => $pathLogo,
            ]);
        }
          
        $boutique = Boutique::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'category' => $request->category,
            'description' => $request->description,
            'site' => $request->site,
            'commande' => 0,
            'visit' => 0,
            'user_id' => Auth::user()->id,
            'logo' => $logo ? $logo->id : null,
        ]);
        $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Boutique $boutique)
    {
        //
    }


    public function getBoutiques(){
        $boutiques = Boutique::where('user_id', Auth::user()->id)->get();
        return $boutiques;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Boutique $boutique)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $boutique = Boutique::find($request->id);
        if($request->hasFile('logo')){
            $imageName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('boutique_medias'), $imageName);
            $pathLogo = $imageName;
            $logo = BoutiqueMedia::create([
                'path' => $pathLogo,
            ]);
        }
        $boutique->update([
            'name' => $request->name,
            'address' => $request->address,
            'site' => $request->site,
            'telephone' => $request->telephone,
            'category' => $request->category,
            'email' => $request->email,
            'description' => $request->description,
            'logo' => $request->hasFile('logo') ? $logo->id : $boutique->logo,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Boutique $boutique)
    {
        //
    }
}
