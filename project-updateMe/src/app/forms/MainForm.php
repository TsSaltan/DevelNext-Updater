<?php
namespace app\forms;

use php\lang\System;
use php\lang\Process;
use bundle\updater\AbstractUpdater;
use bundle\updater\UpdateMe;
use php\gui\framework\AbstractForm;
use php\gui\event\UXWindowEvent; 

/**
 * Updater project
 * @author Ts.Saltan
 * @url https://tssaltan.ru/1531.develnext-updater/ ‎ 
 */
class MainForm extends AbstractForm
{
    /**
     * Текущая версия программы 
     */
    const VERSION = '1.0.0.0';

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->labelVersion->text = 'v' . self::VERSION;

        // Проверка обновлений
        // Обязательно нужно передать текущую версию программы
        UpdateMe::start(self::VERSION);
    }
    
}
