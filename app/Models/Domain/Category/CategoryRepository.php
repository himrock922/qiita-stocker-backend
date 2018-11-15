<?php
/**
 * CategoryRepository
 */

namespace App\Models\Domain\Category;

use App\Models\Domain\AccountEntity;

/**
 * Interface CategoryRepository
 * @package App\Models\Domain
 */
interface CategoryRepository
{
    /**
     * カテゴリを作成する
     *
     * @param AccountEntity $accountEntity
     * @param CategoryNameValue $categoryNameValue
     * @return CategoryEntity
     */
    public function create(AccountEntity $accountEntity, CategoryNameValue $categoryNameValue): CategoryEntity;

    /**
     * カテゴリ一覧を取得する
     *
     * @param AccountEntity $accountEntity
     * @return CategoryEntities
     */
    public function search(AccountEntity $accountEntity): CategoryEntities;
}
