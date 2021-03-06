<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task; //追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        if (\Auth::check()) { // 認証済みの場合
            
    
            //↓で記載の一覧取得→表示の処理を、「認証済みユーザーを取得」→「そのユーザーのタスクの一覧を取得」→表示とする
            // 認証済みユーザを取得
            $user = \Auth::user();
            
            // 当該ユーザのタスクの一覧を取得
            $tasks = $user ->tasks;
            
            //タスク一覧ビューで表示
            return view('tasks.index', [
                'user' =>$user,
                'tasks' =>$tasks,
            ]);
            
            
            /*元のソース
            // タスク一覧を取得
            $tasks = Task::all();
            
            //タスク一覧ビューでそれを表示
            return view('tasks.index', [
                'tasks' =>$tasks,
            ]);
            */
        } else {
        return view('welcome');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;
        
        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' =>$task,
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // postで tasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required',   // 追加
            'status' => 'required|max:10',
        ]);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);        
        
        /*
        // メッセージを作成
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        */
        
        //トップページへのリダイレクト
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = \App\Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は、タスクを表示
        if (\Auth::id() === $task->user_id) {
            $task->tasks;
            return view('tasks.show', [
            'task' => $task,
            ]);
        } else {
        return redirect('/');}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // tasksでmessages/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = \App\Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は、タスク編集ビューでそれを表示
        if (\Auth::id() === $task->user_id) {
            $task->tasks;
            return view('tasks.edit', [
            'task' => $task,
        ]);
    }
        // 前のURLへリダイレクトさせる
        return redirect('/'); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // putまたはpatchで taskss/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        $request->validate([
        'content' => 'required',
        'status' => 'required|max:10',
        ]);
        
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            $task->content = $request->content;
            $task->status = $request->status;
            $task->save();
            
            return redirect('/');
        } else {
        return redirect('/');
        }

        //if (\Auth::check()) {
        // バリデーション
        //$request->validate([
        //    'content' => 'required',   // 追加
        //    'status' => 'required|max:10',
        //]);
        
        // idの値でタスクを検索して取得
        //$task = Task::findOrFail($id);
        // メッセージを更新
        //$task->content = $request->content;
        //$task->status = $request->status;
        //$task->save();

        // トップページへリダイレクトさせる
        //return redirect('/');
        //}
        //else{
        //    return redirect('/');
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/');
        } else {
        return redirect('/');
        }
        //if (\Auth::check()) { 
        //    // idの値でメッセージを検索して取得
        //    $task = Task::findOrFail($id);
        //    // メッセージを削除
        //    $task->delete();
    
        // トップページへリダイレクトさせる
        //    return redirect('/');
        //    }else{
        //    return redirect('/');
        //}
    }
}