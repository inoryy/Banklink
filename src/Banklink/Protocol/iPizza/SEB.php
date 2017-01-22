<?php

namespace Banklink\Protocol\iPizza;

use Banklink\Protocol\iPizza,
    Banklink\Protocol\iPizza\Fields,
    Banklink\Protocol\iPizza\Services;

/**
 * SEB handles MAC generation differently
 *
 * @author Lenar Lõhmus <lenar@city.ee>
 * @since  19.04.2013
 */
class SEB extends iPizza
{
    /**
     * Generate request/response hash based on mandatory fields
     * Overrides iPizza method, because SEB takes lengths in bytes
     *
     * @param array  $data
     * @param string $encoding Data encoding
     *
     * @return string
     *
     * @throws \LogicException
     */
    protected function generateHash(array $data, $encoding = 'UTF-8')
    {
        $id = $data[Fields::SERVICE_ID];

        $hash = '';
        foreach (Services::getFieldsForService($id) as $fieldName) {
            if (!isset($data[$fieldName])) {
                throw new \LogicException(sprintf('Cannot generate %s service hash without %s field', $id, $fieldName));
            }

            $content = $data[$fieldName];
            $hash .= str_pad(strlen($content), 3, '0', STR_PAD_LEFT) . $content;
        }

        return $hash;
    }
}
