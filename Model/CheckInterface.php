<?php

namespace MageSuite\SalesMonitoring\Model;

interface CheckInterface
{
    public function getName(): string;

    public function setName(string $name);

    public function getCriteria(): AlarmCriteria;

    public function setCriteria(AlarmCriteria $criteria);
}
