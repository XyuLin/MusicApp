<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 15:18
 */

namespace Home\Controller;
use Think\Controller;

class NearbyController extends Controller
{

        /**
         * 根据经纬度和半径计算出范围
         * @param string $lat 纬度
         * @param String $lng 经度
         * @param float $radius 半径
         * @return Array 范围数组
         * 1纬度 = 111km
         * 1km = 0.009009纬度
         */
        function calcScope($lat, $lng, $radius) {
            $degree = (24901*1609)/360.0;
            $dpmLat = 1/$degree;

            $radiusLat = $dpmLat*$radius;
            $minLat = $lat - $radiusLat;       // 最小纬度
            $maxLat = $lat + $radiusLat;       // 最大纬度

            $mpdLng = $degree*cos($lat * (PI/180));
            $dpmLng = 1 / $mpdLng;
            $radiusLng = $dpmLng*$radius;
            $minLng = $lng - $radiusLng;      // 最小经度
            $maxLng = $lng + $radiusLng;      // 最大经度

            /** 返回范围数组 */
            $scope = array(
                'minLat'    =>  $minLat,
                'maxLat'    =>  $maxLat,
                'minLng'    =>  $minLng,
                'maxLng'    =>  $maxLng
            );
            return $scope;
        }



        /**
         * 根据经纬度和半径查询在此范围内的所有的人
         * @param  String $lat    纬度
         * @param  String $lng    经度
         * @param  float $radius 半径
         * @return Array         计算出来的结果
         * Longitude             经度
         * Latitude              纬度
         */
        function searchByLatAndLng($lat, $lng, $radius) {
            $scope = $this->calcScope($lat, $lng, $radius);     # 调用范围计算函数，获取最大最小经纬度
            /** 查询经纬度在 $radius 范围内的电站的详细地址 */
            $model = D('location');
            $where = array();
            $where['Latitude'] = array('lt', $scope['maxLat']) and array('gt', $scope['minLat']);
            $where['Longitude'] = array('lt', $scope['maxLng']) and array('gt', $scope['minLng']);
            $info = $model->where($where)->select();

            # 获取用户id 和经纬度 再查处用户的个人信息



        }

}