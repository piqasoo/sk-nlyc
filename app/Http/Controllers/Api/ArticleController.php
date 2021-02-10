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
            
            /*
            ** Query Data
            ** with related model 
            ** Sort data
            */
            $data = Article::Query()
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
            return response($data, 200)->header('Content-Type', 'application/json');
        } catch (\Throwable $th) {
            //throw $th;
            return response(['message' => $th->getMessage()], 400)->header('Content-Type', 'application/json');
        }
        
    }

    public function getArticleComments(Request $request, Article $article){
        try {
            $this->validate($request, [
                'order'     => 'nullable|string|in:asc,desc',
            ]);
            /*
            ** Default parameters
            */

            $sort = 'created_at';
            $order = 'desc';

            /*
            ** Merge parameters
            */
            if(isset($request['order'])){
                $order = $request['order'];
            }
            /*
            ** Get Related Data
            */
            $comments = $article->Comment()
                        ->orderBy($sort, $order)
                        ->get();

            /*
            ** @return json response
            */
            $response = [
                'article_id'        => $article->id,
                'comment_count'    => count($comments),
                'data'              => $comments];
            return response($response, 200)->header('Content-Type', 'application/json');

        } catch (\Throwable $th) {
            //throw $th;
            return response(['message' => $th->getMessage()], 400)->header('Content-Type', 'application/json');
        }
        
    }
}
