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
        $user_icon = db('user')->alias('u')
            ->join('picture p','p.picture_id=u.icon')
            ->column('u.id,u.name,p.path');

        $article_comment = db('article_comment')->alias('ac')
            ->join('user u','u.id=ac.user_id')
            ->join('picture p','p.picture_id=u.icon')
            ->where(['article_id'=>['in',$article_ids]])
            ->group('ac.article_id')
            ->column('ac.article_id,ac.*,group_concat(u.id,":",ac.content) name_comment');
        foreach ($data as &$vo){
            if(isset($likes[$vo['id']])){
                $vo['is_like'] = 1;
            }else{
                $vo['is_like'] = 0;
            }
            if(isset($article_comment[$vo['id']])){
                $comment = explode(',',$article_comment[$vo['id']]['name_comment']);
                foreach ($comment as &$co){
                    $co = explode(':',$co);
                    $co['name']=$user_icon[$co[0]]['name'];
                    $co['path']=$user_icon[$co[0]]['path'];
                    $co['content']=$co[1];
                    unset($co[0]);
                    unset($co[1]);
                }
            }else{
                $comment = [];
            }

            $vo['comment'] = $comment;
        }
        return json_encode($this->mergeData($data));
    }

    //点赞
    public function like($user_id, $article_id)
    {

        $like = db('article_like')->where(['article_id'=>$article_id,'user_id'=>$article_id])->find();
        // 启动事务
        Db::startTrans();
        try {
            if(!empty($like)){
                db('article_like')->insert(['article_id'=>$article_id,'user_id'=>$user_id,'create_time'=>date('Y-m-d H:i:s')]);
                db('article')->where('id',$article_id)->setInc('like_num',1);
            }else{
                db('article_like')->where(['article_id'=>$article_id,'user_id'=>$user_id])->delete();
                db('article')->where(['id'=>$article_id])->setDec('like_num',1);
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
    //评论
    public function comment(Request $request)
    {
        $prm = $request->only('user_id,article_id,comment');
        $data = [
            'user_id'=>$prm['user_id'],
            'article_id'=>$prm['article_id'],
            'content'=>$prm['comment'],
            'create_time'=>date('Y-m-d H:i:s'),
        ];
        // 启动事务
        Db::startTrans();
        try {
            db('article_comment')->insert($data);
            db('article')->where('id',$prm['article_id'])->setInc('content_num',1);

            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

    //删除
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

    //发布文章
    public function addArticle(Request $request)
    {
        $prm = $request->only('user_id,title,info,picture_ids');
        $prm['create_time'] = date('Y-m-d H:i:s');
        try {
            db('article')->insert($prm);
            // 提交事务
            Db::commit();
            return json_encode($this->mergeData());
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->wrong(603, $e->getMessage());
        };
    }

    //上传图片
    public function uploadPictures()
    {
        $avatar = request()->file('image');
        $info = $avatar->move(PUBLIC_PATH . DS . 'uploads');
        if ($info) {
            $return['path'] = '/uploads/' . $info->getSaveName();
            // 启动事务
            Db::startTrans();
            try {
                //获取picture_id
                $picture_id = Db::name('picture')->insertGetId(array('path' => $return['path']));
                // 提交事务
                Db::commit();
                return json_encode($this->mergeData($picture_id));
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $this->wrong('401', $e->getMessage());
            }
        } else {
            // 上传失败获取错误信息
            $this->wrong('401', $avatar->getError());
        }
    }

}
