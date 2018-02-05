<?php
namespace app\forms;

use php\framework\Logger;
use bundle\updater\Updater;
use bundle\updater\GitHubUpdater;
use php\gui\framework\AbstractForm;
use php\gui\event\UXWindowEvent; 
use php\gui\event\UXEvent; 


class UpdateForm extends AbstractForm
{
    /**
     * @var GitHubUpdater 
     */
    public $updater;
    

    /**
     * @event buttonDownload.action 
     */
    function doButtonDownloadAction(UXEvent $e = null)
    {
        // Отправляем приложению сигнал на закрытие программы
        $this->updater->closeParentApplication();
        
        // Показываем прогресс-бар
        $this->buttonDownload->visible = 
        $this->buttonClose->visible = 
        !$this->progressBar->visible = 
         $this->labelStatus->visible = true;
        
        // Запуск процесса обновления
        $this->updater->installUpdate(function(string $status, int $percent){
            // Обновляем данные на форме        
            $statusText = ['download' => 'Загрузка', 'install' => 'Установка', 'complete' => 'Готово'][$status];
            $this->labelStatus->text = $statusText . ' ('.$percent.'%)';
            $this->progressBar->progress = $percent;
            
            if($status == 'complete'){
                // По завершению
                $this->closeUpdater();
            }
        });
    }    
    
    /**
     * @event buttonClose.action 
     * @event close 
     */
    function closeUpdater(){   
        Logger::info('Close updater');
        exit;
    }
}
