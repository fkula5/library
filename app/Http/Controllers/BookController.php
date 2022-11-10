<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Isbn;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BookRepository $bookRepo)
    {
        $booksList = $bookRepo->getAll();
        return view('books/list', ['booksList' => $booksList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::all();
        return view('books/create', ['authors' => $authors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BookRepository $bookRepo)
    {
        $data = $request->all();
        $bookRepo->create($data);
        return redirect('books');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookRepository $bookRepo, $id)
    {
        $book = $bookRepo->find($id);
        return view('books/show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BookRepository $bookRepo, $id)
    {
        $data = [
            'name' => 'Quo Vadis',
            'year' => 2001,
            'publication_place' => 'Warszawa',
            'pages' => 650,
            'price' => 59.99
        ];

        $bookRepo->update($data, $id);
        return redirect('books');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookRepository $bookRepo, $id)
    {
        $bookRepo->delete($id);
        return redirect('books');
    }
    public function cheapest(Book $book){
        $booksList = DB::table('books')->orderBy('price', 'asc')->limit(3)->get();
        return view('books/list',['booksList' => $booksList]);
    }

    public function longest(Book $book){
        $booksList = DB::table('books')->orderBy('pages', 'desc')->limit(3)->get();
        return view('books/list',['booksList' => $booksList]);
    }

    public function search(Request $request, Book $book){
        $q = $request->input('q',"");
        $booksList = DB::table('books')->where('name', 'like', '%'.$q.'%')->get();
        return view('books/list',['booksList' => $booksList]);
    }
}
