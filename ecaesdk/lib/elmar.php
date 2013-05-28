<?php
/**
 * ECAE SDK 模拟ecae Storage的存储
 * 
 * @author: wubin@shopex.cn
 * @create: 2012-02-22 01:45
 */

/**
 * ecae 存文件
 *
 * @param string $group_id // 桶信息
 * @param string $local_file // 本地文件地址
 * @param array  $options  array(
 *                            "name"=>"xxx", // 要存储的名字
 *                            "path"=>"xxx", // 存储路径
 *                         )
 *
 * @return string // ID 用于取回文件
 */
function ecae_file_save($bucket,$local_file,$options = array()) {
    return ecaesdk_file::getInstance("file")->store($bucket,$local_file,$options);
}

/**
 * ecae 删除指定文件
 *
 * @param string $file_id
 * @return boolean
 */
function ecae_file_delete($file_id) {
    return ecaesdk_file::getinstance("file")->delete($file_id);
}

/**
 * ecae 获取指定文件内容
 *
 * @param string $file_id
 * @param string $filename // 要保存的文件
 * @return boolean
 */
function ecae_file_fetch($file_id,$filename) {
    return file_put_contents($filename,ecaesdk_file::getInstance("file")->fetch($file_id));
}

/**
 * ecae 替换指定文件内容
 *
 * @param string $file_id
 * @param string $local_file // 本地文件地址
 * @return string
 */
function ecae_file_replace($file_id,$local_file) {
    return ecaesdk_file::getInstance("file")->replace($file_id,$local_file);
}

/**
 * ecae 获取指定文件的访问链接
 *
 * @param string $file_id
 * @return string
 */
function ecae_file_url($file_id) {
    return ecaesdk_file::getInstance("file")->getUrl($file_id);
}

/**
 * ecae 获取所有桶信息
 *
 * @return array
 */
function ecae_file_bucket_list() {
    $arrData = ecaesdk_file::getInstance("file")->getBucketList();
    $arrResult = array(
        array(
            'bucket_id'=>constant("ECAE_SITE_NAME")."-public",
            'create_date'=>time(),
        ),
        array(
            'bucket_id'=>constant("ECAE_SITE_NAME")."-private",
            'create_date'=>time(),
        ),
        array(
            'bucket_id'=>constant("ECAE_SITE_NAME")."-images",
            'create_date'=>time(),
        ),
    );
    return array_merge($arrData,$arrResult);
}
