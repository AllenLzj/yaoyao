<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

//指定其他域名访问
header('Access-Control-Allow-Origin:*');

class Article extends ApiBase
{

    //文章列表
    public function index($user_id)
    {
        $data = db('article')->select();
        $article_ids = array_column($data,'id');
        $likes = db('article_like')->where(['user_id'=>$user_id])->column('article_id,id');
        $article_comment = db('article_comment')->alias('ac')
            ->join('user u','u.id=ac.user_id')
            ->where(['article_id'=>['in',$article_ids]])
            ->group('ac.article_id')
            ->column('ac.article_id,ac.*,group_concat(u.name,":",ac.content) name_comment');

        foreach ($data as &$vo){
            if(isset($likes[$vo['id']])){
                $vo['is_like'] = 1;
            }else{
                $vo['is_like'] = 0;
            }
            $comment = explode(',',$article_comment[$vo['id']]['name_comment']);
            foreach ($comment as &$co){
                $co = explode(':',$co);
                $co['name']=$co[0];
                $co['content']=$co[1];
                unset($co[0]);
                unset($co[1]);
            }
            $vo['comment'] = $comment;
        }
        return json_encode($this->mergeData($data));
    }

    public function like($user_id, $article_id)
    {
        db('article')->where('id',$article_id)->setInc('like',1);
        echo
        $like = db('article_like')->where(['article_id'=>$article_id,'user_id'=>$article_id])->value('like');
        // 启动事务
        Db::startTrans();
        try {
            if(!empty($like)){
                db('article_like')->insert(['article_id'=>$article_id,'user_id'=>$user_id,'create_time'=>date('Y-m-d H:i:s')]);
                db('article')->where('id',$article_id)->setInc('like',1);
            }else{
                db('article_like')->where(['article_id'=>$article_id,'user_id'=>$user_id])->delete();
                db('article')->where(['id'=>$article_id])->setDec('like',1);
            }
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

    public function comment(Request $request)
    {
        $prm = $request->only('user_id,article_id,comment');
        $data = [
            'user_id'=>$prm['user_id'],
            'article_id'=>$prm['article_id'],
            'comment'=>$prm['comment'],
            'create_time'=>date('Y-m-d H:i:s'),
        ];
        // 启动事务
        Db::startTrans();
        try {
            db('article_comment')->insert($data);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

    public function delArticle($user_id, $article_id)
    {
        // 启动事务
        Db::startTrans();
        try {
            db('article')->where(['user_id'=>$user_id,'article_id'=>$article_id])->delete();
            db('article_like')->where(['article_id'=>$article_id])->delete();
            db('article_comment')->where(['article_id'=>$article_id])->delete();
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

}
