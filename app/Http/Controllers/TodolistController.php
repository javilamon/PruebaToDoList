<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use Illuminate\Http\Request;

class TodolistController extends Controller
{
   
    public function index()
    {
        //
        $todolist= Todolist::all();
        return view('home', compact('todolist'));

    }


    public function store(Request $request)
    {
       $data = $request->validate([
           'content' => 'required'
        ]);
        $todoListElement = new Todolist();
        $todoListElement->content = $data['content'];
        $todoListElement->save();

        $todoListElement = $todoListElement->refresh();
        
        return $todoListElement;


        //
    }

    public function destroy(todolist $todolist)
    {
        //
        $todolist->delete();
        return back();
    }
    public function getall(Request $request){
        $ResponseToSend=Todolist::all();
        return $ResponseToSend;


    }

    public function delete(Request $request){

        $data = $request->validate([
            'pk' => 'required'
         ]);
        // dd($request-all());
        $ResponseToSend=Todolist::where('id',$data)->delete();

          return $ResponseToSend;
    
    }

    public function updateStateToDoItem(Request $request){
        $data = $request->validate([
            'pk'=>'required',
            'done'=>'required'
        ]);

        $ResponseToSend = Todolist::where('id',$data["pk"])->update(['done'=>$data["done"]]);
        return $ResponseToSend;
    }

    public function searchExistingTodo(Request $request){
        $data = $request->validate([
            'textTodo'=>'required'
        ]);
            
        $ResponseToSend = Todolist::where('content','like','%'.$data["textTodo"].'%')->get();
        return $ResponseToSend;
    }

    public function updateItemTodoByPk(Request $request){
        $data = $request->validate([
            'pk'=>'required',
            'textTodo'=>'required'
        ]);

        $ResponseToSend = Todolist::where('id',$data["pk"])->update(['content'=>$data["textTodo"]]);
        return $ResponseToSend;
    }
}
