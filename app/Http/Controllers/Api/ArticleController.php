<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    public function getAll(Request $request){
        /*
        ** Default parameters
        */
        $sort = 'created_at';
        $order = 'desc';
        $paginate = null;
        $offset = 0;
        $page = 1;
        $limit = 10;

        /*
        ** Merge parameters
        */
        if(isset($request['sort']) && in_array($request['sort'], ['view_count', 'comment_count', 'created_at'])){
            $sort = $request['sort'];
        }
        if(isset($request['order']) && in_array($request['order'], ['asc', 'desc'])){
            $order = $request['order'];
        }
        if(isset($request['paginate'])){
            $paginate = intval($request['paginate']);
        }
        if(isset($request['limit'])){
            $limit = intval($request['limit']);
        }

        if(isset($request['page'])){
            $page = intval($request['page']);
            if($page > 1){
                $offset = ($page - 1) * $limit;
            }
        }
        
        /*
        ** Get Data
        */
        $data = Article::with('tags')->get();
        
        /*
        ** Order Data
        */
        if($order != 'desc'){
            $data = $data->sortBy($sort);
        }else{
            $data = $data->sortByDesc($sort);
        }

        /*
        ** Paginate Data
        */
        if($paginate){
            $data = collect($data)->slice($offset, $limit);
        }

        /*
        ** return data without keys
        */
        $data = array_values($data->toArray());

        /*
        ** @return json response
        */
        return response()->json([
            'statusCode' => count($data) ? 200 : 400,
            'all_record'   => count(Article::get()),
            'paginate' => $paginate ? [
                'page'      => $page,
                'per_page'  => $limit,
            ] : false,
            'order'     => $order,
            'data' => $data]);
    }

    public function getArticleComments(Request $request, $id){
        /*
        ** Default parameters
        */
        $sort = 'created_at';
        $order = 'desc';

        /*
        ** Merge parameters
        */
        if(isset($request['order']) && in_array($request['order'], ['asc', 'desc'])){
            $order = $request['order'];
        }

        if($id){

            /*
            ** @param $id
            ** Get Main Data
            */
            $data = Article::find($id);

            /*
            ** @return json 404 response
            */
            if(!$data){
                return response()->json([
                    'statusCode' => 404,
                    'data' => []
                ]);
            }

            /*
            ** Get Related Data
            */
            $comments = $data->Comments()->get();

            /*
            ** Order Related Data
            */
            if($order != 'desc'){
                $comments = $comments->sortBy($sort);
            }else{
                $comments = $comments->sortByDesc($sort);
            }

            /*
            ** Return related data without keys
            */
            $comments = array_values($comments->toArray());

            /*
            ** @return json response
            */
            return response()->json([
                'statusCode'        => count($comments) ? 200 : 400,
                'article_id'        => $data->id,
                'comments_count'    => count($comments),
                'data'              => $comments]);
        }
    }
}
