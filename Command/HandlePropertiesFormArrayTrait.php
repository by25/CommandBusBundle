<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Command;

trait HandlePropertiesFormArrayTrait
{
    private function handleProperties(array $array)
    {
        foreach ($array as $property => $value) {
            if (property_exists($this, $property)) {
                if ($this->{$property} === null) {
                    $this->{$property} = $value;
                } elseif (is_array($this->{$property}) && !count($this->{$property})) {
                    $this->{$property} = $value;
                }
            }
        }
    }
}
