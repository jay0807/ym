<?php
/**
 * ECAE SDK 模拟ecae rsa的加密服务
 * 
 * @author: wubin@shopex.cn
 * @create: 2012-02-22 01:55
 * @update: 2012-02-25 01:19
 */

/**
 * ecae rsa 初始化
 *
 * @param string $key
 * @param string $value
 * @return boolean
 */
function ecae_rsa_init() {
    return true;
}

/**
 * ecae rsa 加密
 *
 * @param string $encode
 * @return string
 */
function ecae_rsa_encrypt($encode) {
    return  base64_encode(ecaesdk_utils::xorEncode($encode,ECAE_SITE_ID));
}

/**
 * ecae rsa 解密
 *
 * @param string $decode
 * @return string
 */
function ecae_rsa_decrypt($decode) {
    return (ecaesdk_utils::xorDecode(base64_decode($decode),ECAE_SITE_ID));
}
