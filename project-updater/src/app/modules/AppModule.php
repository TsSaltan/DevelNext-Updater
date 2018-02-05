<?php
namespace app\modules;

use php\framework\Logger;
use php\gui\framework\AbstractModule;
use php\gui\framework\ScriptEvent; 
use bundle\updater\GitHubUpdater;
use bundle\updater\Updater;

/**
 * Updater project
 * @author Ts.Saltan
 * @url https://tssaltan.ru/1531.develnext-updater/ ‎ 
 */
class AppModule extends AbstractModule
{

    /**
     * @event action 
     */
    function construct(){    
        // Берём аргументы, переданные программе на запуск
        $currentVersion = $GLOBALS['argv'][1] ?? '0.0.0.1'; // 1й - версия проргаммы
        $origFile = $GLOBALS['argv'][2] ?? 'test.exe';  // 2й - имя exe-шника программы, которую нужно обновить
        
        // Указываеи имя пользователя и название репозитория
        $updater = new GitHubUpdater('TsSaltan', 'DevelNext-Updater');
        $updater->setCurrentVersion($currentVersion); // Необходим сообщить текущую версию программы, чтоб сравнить её с версией на сервере
        $updater->setOrigFile($origFile); // Перед обновлением программа будет закрыта, а после - запущена заново
        
        Logger::info('Current version: ' . $currentVersion);
        Logger::info('Checking updates');
        
        // Запускаем проверку обновлений
        $updater->checkUpdates(function(bool $check, array $info) use ($updater, $origFile, $currentVersion){
            if(!$check){
                // Если обновлений не было, просто завершаем работу программы
                Logger::info('Update does not found');
                die;
            } else {        
                // Если же обновления есть - показываем форму
                Logger::info('Found new version: ' . $info['version']);
                
                $form = $this->form('UpdateForm');
                $form->updater = $updater;
                $form->label->text .= ' (' . round($info['size']/1024/1024, 2) . ' MiB)';
                $form->labelVersion->text = $info['version'];
                $form->description->text = $info['description'];
                $form->labelCurrent->text = 'Текущая версия программы ' . basename($origFile) . ': ' . $currentVersion;
                $form->show();
            }
        });
    }

}
