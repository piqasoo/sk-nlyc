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

        try {

            $this->validate($request, [
                'sort'      => 'nullable|string|in:article_count,created_at',
                'order'     => 'nullable|string|in:asc,desc',
            ]);
            /*
            ** Merge parameters
            */
            if(isset($request['sort'])){
                $sort = $request['sort'];
            }
            if(isset($request['order'])){
                $order = $request['order'];
            }  
            
            /*
            ** Query Data
            ** with related model 
            ** Sort data
            ** get data
            */
            $data = Tag::Query()
                    ->withCount('article')
                    ->orderBy($sort, $order)
                    ->get();

            /*
            ** @return json response
            */
            $response = [
                'records'   => count($data),
                'data'      => $data];
            return response($response, 200)->header('Content-Type', 'application/json');
        } catch (\Throwable $th) {
            //throw $th;
            return response(['message' => $th->getMessage()], 400)->header('Content-Type', 'application/json');
        }

        
    }

    public function getTagArticles(Request $request, $id){
        /*
        ** Default parameters
        */
        $sort = 'created_at';
        $order = 'desc';
        $paginate = null;
        $page = 1;
        $limit = 10;

        try {
            $this->validate($request, [
                'sort'      => 'nullable|string|in:view_count,comment_count,created_at',
                'order'     => 'nullable|string|in:asc,desc',
                'paginate'  => 'nullable|boolean',
                'limit'     => 'nullable|numeric',
                'page'      => 'nullable|numeric',
            ]);

            /*
            ** Merge parameters
            */
            if(isset($request['sort'])){
                $sort = $request['sort'];
            }
            if(isset($request['order'])){
                $order = $request['order'];
            }
            if(isset($request['paginate'])){
                $paginate = $request['paginate'];
            }
            if(isset($request['limit'])){
                $limit = $request['limit'];
            }
            if(isset($request['page'])){
                $page = $request['page'];
            }

            $tag = Tag::findOrFail($id);
            /*
            ** Query Data
            ** with related model 
            ** Sort data
            */
            $data = $tag->Article()
                        ->withCount('comment')
                        ->orderBy($sort, $order);

            /*
            ** Get Data
            */
            if($paginate){
                $data = $data->simplePaginate($limit);
            }else{
                $data = $data->get();
            }
            
            /*
            ** @return json response
            */
            $response = [
                'tag_id'            => $tag->id,
                'articles_count'    => count($tag->Article()->get()),
                'data'              => $data
            ];
            return response($response, 200)->header('Content-Type', 'application/json');
            
        } catch (\Throwable $th) {
            //throw $th;
            return response(['message' => $th->getMessage()], 400)->header('Content-Type', 'application/json');
        }
    } 
}
