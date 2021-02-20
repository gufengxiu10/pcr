<?php

declare(strict_types=1);

namespace app\api\music\contract;

interface Api
{
    /**
     * @name: 搜索
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-19 09:56:55
     * @return {*}
     */
    public function search(string $searchWord);
}
