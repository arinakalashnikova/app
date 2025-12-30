<?php
require_once __DIR__ . '/OrderRepositoryInterface.php';
class DBRepository implements OrderRepositoryInterface{
    private PDO $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    function hold($sku, $price){
        //находим
        $this->pdo->beginTransaction();
        $select = $this->pdo->prepare("select id from for_tasks.stock where sku = :sku and price = :price and state = 'Stock' limit 1 for update");
        $select->execute([':sku' => $sku, ':price'=> $price]);
        $id = $select->fetchColumn();
        if (!$id) {
            $this->pdo->rollBack();
            return null;
        }
        //записываем резервирование заказа
        $newState = 'Hold/ORDER' . substr($sku,3);
        $select = $this->pdo->prepare("update for_tasks.stock set state = :state where id = :id");
        $select->execute([':state'=>$newState, ':id'=>$id ]);

        $this->pdo->commit();
        return $id;
    }
    function confirm($order){
        //находим
        $this->pdo->beginTransaction();
        $select = $this->pdo->prepare("select id from for_tasks.stock where state = :state limit 1 for update");
        $select->execute([':state'=>$order]);
        $id = $select->fetchColumn();
        if (!$id) {
            $this->pdo->rollBack();
            return null;
        }
        //записываем подтверждение заказа
        $select = $this->pdo->prepare("update for_tasks.stock set state = 'Sold' where id = :id");
        $select->execute([':id'=>$id]);

        $this->pdo->commit();
        return $id;
    }
}

