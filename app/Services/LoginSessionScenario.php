<?php
/**
 * LoginSessionScenario
 */

namespace App\Services;

use Ramsey\Uuid\Uuid;
use App\Models\Domain\QiitaAccountValue;
use App\Models\Domain\Account\AccountEntity;
use App\Models\Domain\QiitaAccountValueBuilder;
use App\Models\Domain\Account\AccountRepository;
use App\Models\Domain\Exceptions\ValidationException;
use App\Models\Domain\Exceptions\AccountNotFoundException;
use App\Models\Domain\LoginSession\LoginSessionEntityBuilder;
use App\Models\Domain\LoginSession\LoginSessionSpecification;

/**
 * Class LoginSessionScenario
 * @package App\Services
 */
class LoginSessionScenario
{
    /**
     * AccountRepository
     *
     * @var
     */
    private $accountRepository;

    /**
     * LoginSessionScenario constructor.
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * ログインセッションを作成する
     *
     * @param array $requestArray
     * @return array
     * @throws AccountNotFoundException
     * @throws ValidationException
     */
    public function create(array $requestArray): array
    {
        try {
            $errors = LoginSessionSpecification::canCreate($requestArray);
            if ($errors) {
                throw new ValidationException(QiitaAccountValue::createLoginSessionValidationErrorMessage(), $errors);
            }

            $qiitaAccountValueBuilder = new QiitaAccountValueBuilder();
            $qiitaAccountValueBuilder->setAccessToken($requestArray['accessToken']);
            $qiitaAccountValueBuilder->setUserName($requestArray['qiitaAccountId']);
            $qiitaAccountValueBuilder->setPermanentId($requestArray['permanentId']);
            $qiitaAccountValue = $qiitaAccountValueBuilder->build();

            if (!$qiitaAccountValue->isCreatedAccount($this->accountRepository)) {
                throw new AccountNotFoundException(AccountEntity::accountNotFoundMessage());
            }

            $accountEntity = $qiitaAccountValue->findAccountEntityByPermanentId($this->accountRepository);

            \DB::beginTransaction();

            $accountEntity = $accountEntity->updateAccessToken($this->accountRepository, $qiitaAccountValue);
            $sessionId = Uuid::uuid4();

            // TODO 有効期限を適切な期限に修正
            $expiredOn = new \DateTime();
            $expiredOn->add(new \DateInterval('PT1H'));

            $loginSessionEntityBuilder = new LoginSessionEntityBuilder();
            $loginSessionEntityBuilder->setAccountId($accountEntity->getAccountId());
            $loginSessionEntityBuilder->setSessionId($sessionId);
            $loginSessionEntityBuilder->setExpiredOn($expiredOn);
            $loginSessionEntity = $loginSessionEntityBuilder->build();


            $this->accountRepository->saveLoginSession($loginSessionEntity);

            \DB::commit();
        } catch (\PDOException $e) {
            \DB::rollBack();
            throw $e;
        }

        $responseArray = [
            'sessionId' => $loginSessionEntity->getSessionId()
        ];

        return $responseArray;
    }
}
