<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\BoutiqueMedia;
use Inertia\Inertia;
use App\Models\Boutique;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        return Inertia::render('Search');
    }

    public function searchArticle(Request $request)
    {
        $term = $request->term;
        // dd($term);

        // Effectuer la recherche dans la base de données
        $ArtResults = Article::where('name', 'LIKE', '%' . $term . '%')
            ->limit(20)
            ->with('articleMedia')
            ->orderBy('created_at',  'DESC')
            ->get();

        return $ArtResults;
    }

    public function searchBoutique(Request $request)
    {
        $term = $request->term;
        $BoutResults = Boutique::where('name', 'LIKE', '%' . $term . '%')
            ->orderBy('created_at', 'desc')
            ->limit(20) 
            ->get();

        for ($i=0; $i < count($BoutResults); $i++) { 
            $logo = BoutiqueMedia::find($BoutResults[$i]['logo']);
            $BoutResults[$i]['image'] = $logo;
        }
        return $BoutResults;
    }

}
