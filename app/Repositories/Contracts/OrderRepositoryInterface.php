<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function create(array $data);
    public function createItem(array $data);
    public function find($id);
    public function getTodayTurnover();
    public function getTodayOrderCount();
    public function getReportData($startDate, $endDate);
}
