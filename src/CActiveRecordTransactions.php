<?php


class CActiveRecordTransactions
{
    /**
     * Keeps transaction status (true if previous transaction, transaction if
     * local transaction, null if no transaction).
     *
     * @var CDbTransaction bool null
     */
    private $transaction = null;

    public function transactional(callable $fn)
    {
        $return = null;
        try {
            $this->beginTransaction();
            $return = $fn($this);
            $this->commit();
        } catch (Exception $exception) {
            $this->rollback();
            throw $exception;
        }
        return $return;

    }

    /**
     * Begin a transaction on the database.
     */
    public function beginTransaction() {
        
        $this->beforeTransactionBegin();
        
        if($this->transaction !== null) {
            throw new Exception('Transaction already started');
        }
        $connection = $this->getDbConnection();
        $transaction = $connection->getCurrentTransaction();
        if($transaction) {
            $this->transaction = true;
        } else {
            $this->transaction = $connection->beginTransaction();
        }
        $this->afterTransactionBegin();
    }

    /**
     * Commit a transaction on the database.
     *
     * @throws Exception
     */
    public function commit() {
        $this->beforeCommit();
        if($this->transaction === null) {
            throw new Exception('No ongoing transaction');
        } elseif($this->transaction !== true){
            $this->transaction->commit();
        }
        $this->transaction = null;
        $this->afterCommit();
    }

    /**
     * Rollback a transaction on the database.
     *
     * @throws Exception
     */
    public function rollback() {
        $this->beforeRollback();
        if($this->transaction === null) {
            throw new Exception('No ongoing transaction');
        } elseif($this->transaction !== true){
            $this->transaction->rollback();
        }
        $this->transaction=null;
        $this->afterRollback();
    }
    public function beforeCommit()
    {

    }
    public function afterCommit()
    {

    }
    public function beforeTransactionBegin()
    {

    }
    public function afterTransactionBegin()
    {

    }
    public function beforeRollback()
    {

    }
    public function afterRollback()
    {

    }
}
