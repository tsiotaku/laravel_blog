<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function index(){
        echo "顯示全部文章";
    }
    public function create(){
        $cate_data = [];
        return view('admin.article.add',compact('cate_data'));
    }
}