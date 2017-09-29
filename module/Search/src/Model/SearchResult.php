<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.09.17
 * Time: 17:34
 */

namespace Search\Model;

//Класс для обработки результата
use Mobileinspector\Basic\JsonClientResponse;

class SearchResult
{
    public function getServiceResponse($service)
    {
        return $this->formatForClient(
            $service->result,
            empty($service->result),
            empty($service->comment) ? '' : $service->comment
        );
    }

    /**
     * Приведение к другому формату
     * @param $data
     * @param bool $operation_result
     * @param string $comment
     * @return string
     */
    public function formatForClient($data, $operation_result = false, $comment = '')
    {
        if (empty($comment)) {

            $comment_out = $operation_result ? 'Не удалось выполнить запрос' : '';

        } else {

            $comment_out = $comment;
        }
        $format = new JsonClientResponse($comment_out, $data, $operation_result);
        return $format->toString();
    }
}