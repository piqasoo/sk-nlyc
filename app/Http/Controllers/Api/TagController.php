<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function getAll(Request $request){

        /*
        ** Default parameters
        */
        $sort = 'article_count';
        $order = 'desc';

        /*
        ** Merge parameters
        */
        if(isset($request['sort']) && in_array($request['sort'], ['article_count', 'created_at'])){
            $sort = $request['sort'];
        }
        if(isset($request['order']) && in_array($request['order'], ['asc', 'desc'])){
            $order = $request['order'];
        }        

        /*
        ** Get Data
        */
        $data = Tag::get();

        /*
        ** Order Data
        */
        if($order != 'desc'){
            $data = $data->sortBy($sort);
        }else{
            $data = $data->sortByDesc($sort);
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
            'records'   => count($data),
            'data' => $data]);
    }

    public function getTagArticles(Request $request, $id){

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

        if($id){
            /*
            ** @param $id
            ** Get Main Data
            */
            $data = Tag::find($id);

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
            $articles = $data->Articles()->get();

            /*
            ** Order Related Data
            */
            if($order != 'desc'){
                $articles = $articles->sortBy($sort);
            }else{
                $articles = $articles->sortByDesc($sort);
            }

            /*
            ** Paginate Related Data
            */
            if($paginate){
                $articles = collect($articles)->slice($offset, $limit);
            }

            /*
            ** Return related data without keys
            */
            $articles = array_values($articles->toArray());

            /*
            ** @return json response
            */
            return response()->json([
                'statusCode'        => count($articles) ? 200 : 400,
                'tag_id'            => $data->id,
                'paginate' => $paginate ? [
                    'page'      => $page,
                    'per_page'  => $limit,
                ] : false,
                'articles_count'    => count($data->Articles()->get()),
                'data'              => $articles]);
        }
    } 
}
