<?php
 
namespace Drupal\my_database\Controller;
 
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides route responses for the Example module.
 */

class MyPageController extends ControllerBase{
    
    public function mypage() {
        $header = [
            'id' => t('id'),
            'number' => t('number'),
            'teaser' => t('teaser'),
            'text' => t('text'),
            'actions' => t('actions'),
        ];
        
        $query = \Drupal::database()->select('my_database', 'md');
        $query->fields('md', ['id', 'number', 'teaser', 'text']);
        $query->orderBy('id', 'ASC');
        
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {
            $link = Link::fromTextAndUrl(t('edit'.' | '), Url::fromRoute('my_database_edit', array('id' => $data->id)))->toString();
           $link_del = Link::fromTextAndUrl(t('delete'), Url::fromRoute('my_database_delete', array('id' => $data->id, )))->toString();
           // $link_del =  Url::fromRoute('my_database_delete'.$data->id);
            $rows[] = array(
                'id' => $data->id,
                'number' => $data->number,
                'teaser' => $data->teaser,
                'text' => $data->text,
                'actions' => t('@link @link_del', array('@link' => $link, '@link_del' => $link_del)),


            );
        }

        $build['table_pager'][] = array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => t('No records found!'),
        );


        return $build;
    }
}