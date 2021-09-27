<?php
// скорее всего будут новые виды роботов, поэтому используем интерфейс и проверяем его наличие в фабрике
// с интерфейсами раньше не сталкивался
namespace robots;

interface InterfaceRobot {
    // параметры роботов передаём методами, чтобы их нельзя было перебить
    function getSpeed();
    function getWeight();
    function getHeight();
}
