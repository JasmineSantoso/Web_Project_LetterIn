<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    public function browse()
    {
        return view('books.browse');
    }

    public function details($id)
    {
        return view('books.details', compact('id'));
    }

    public function search()
    {
        return view('books.search');
    }

    public function addReview($id)
    {
        return view('books.add_review', compact('id'));
    }
}
