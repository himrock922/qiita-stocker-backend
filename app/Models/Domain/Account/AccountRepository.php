<?php
/**
 * AccountRepository
 */

namespace App\Models\Domain\Account;

use App\Models\Domain\QiitaAccountValue;

/**
 * Interface AccountRepository
 * @package App\Models\Domain
 */
interface AccountRepository
{
    /**
     * アカウントを作成する
     *
     * @param QiitaAccountValue $qiitaAccountValue
     * @return AccountEntity
     */
    public function create(QiitaAccountValue $qiitaAccountValue): AccountEntity;

    /**
     * パーマネントIDからアカウントを取得する
     *
     * @param QiitaAccountValue $qiitaAccountValue
     * @return AccountEntity
     */
    public function findByPermanentId(QiitaAccountValue $qiitaAccountValue): AccountEntity;

    /**
     * アクセストークンを更新する
     *
     * @param AccountEntity $accountEntity
     * @param QiitaAccountValue $qiitaAccountValue
     */
    public function updateAccessToken(AccountEntity $accountEntity, QiitaAccountValue $qiitaAccountValue);

    /**
     * ユーザ名を更新する
     *
     * @param AccountEntity $accountEntity
     * @param QiitaAccountValue $qiitaAccountValue
     */
    public function updateQiitaUserName(AccountEntity $accountEntity, QiitaAccountValue $qiitaAccountValue);

    /**
     * アカウントを取得する
     *
     * @param string $accountId
     * @return AccountEntity
     */
    public function find(string $accountId): AccountEntity;

    /**
     * Qiitaアカウントを削除する
     *
     * @param string $accountId
     */
    public function destroyQiitaAccount(string $accountId);

    /**
     * Qiitaユーザ名を削除する
     *
     * @param string $accountId
     */
    public function destroyQiitaUserName(string $accountId);

    /**
     * アクセストークンを削除する
     *
     * @param string $accountId
     */
    public function destroyAccessToken(string $accountId);

    /**
     * アカウントを削除する
     *
     * @param string $accountId
     */
    public function destroyAccount(string $accountId);
}
