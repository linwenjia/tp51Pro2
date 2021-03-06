<?php

namespace app\cms\controller;

use app\common\controller\CmsBase;
use app\common\model\XadLists;
use think\Request;

/**
 * 广告管理处理类
 * Class AdList
 * @package app\cms\Controller
 */
class AdList extends CmsBase
{
    private $adModel;
    public function __construct()
    {
        parent::__construct();
        $this->adModel = new XadLists();
    }

    /**
     * 广告列表页
     * @param Request $request
     * @return \think\response\View
     */
    public function index(Request $request){
        $search = $request->param('str_search');
        $curr_page = $request->param('curr_page',1);
        if ($request->isPost()){
            $list = $this->adModel->getAdsForPage($curr_page,$this->page_limit,$search);
            return showMsg(1,'success',$list);
        }else{
            $record_num = $this->adModel->getAdsCount($search);
            $list = $this->adModel->getAdsForPage($curr_page,$this->page_limit,$search);
            return view('index',
                [
                    'ads' => $list,
                    'search' => $search,
                    'record_num' => $record_num,
                    'page_limit' => $this->page_limit,
                ]);
        }
    }

    /**
     * 增加广告功能
     * @param Request $request
     * @return \think\response\View|void
     */
    public function add(Request $request){
        if ($request->isPost()){
            $input = $request->post();
            $opRes = $this->adModel->addAdvertisement($input);
            return showMsg($opRes['tag'],$opRes['message']);
        }else{
            return view('add');
        }
    }

    /**
     * 编辑数据
     * @param Request $request
     * @param $id 广告ID
     * @return \think\response\View|void
     */
    public function edit(Request $request,$id){
        $actData = $this->adModel->getAdByID($id);
        if ($request->isPost()){
            $input = $request->post();
            $opRes = $this->adModel->editAdvertisement($id,$input);
            return showMsg($opRes['tag'],$opRes['message']);
        }else{
            return view('edit',[
                'adData'   => $actData,
            ]);
        }
    }

    /**
     * ajax 更改首页显示状态
     * @param Request $request
     */
    public function ajaxForShow(Request $request){
        $opRes = $this->adModel->updateForShow( $request->post('act_id'),$request->post('okStatus'));
        return showMsg($opRes['tag'],$opRes['message']);
    }

}
