<?php
/**
 * StockSpecification
 */

namespace App\Models\Domain\Stock;

/**
 * Class StockSpecification
 * @package App\Models\Domain\Stock
 */
class StockSpecification
{
    /**
     * ストックが検索可能か確認する
     *
     * @param array $requestArray
     * @return array
     */
    public static function canfetchStocks(array $requestArray): array
    {
        $validator = \Validator::make($requestArray, [
            'page'       => 'required|integer|min:1|max:100',
            'perPage'    => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return [];
    }
}