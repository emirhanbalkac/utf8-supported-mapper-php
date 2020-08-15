<?php
/**
 * Created by PhpStorm.
 * User: milos.pejanovic
 * Date: 5/31/2016
 * Time: 2:42 PM
 */

namespace Traits;

use Mapper\ModelMapper;
use Mapper\ModelMapperException;
use Mapper\XmlModelMapper;

trait MappableTrait
{

    /**
     * @param array $data
     *
     * @throws \InvalidArgumentException
     * @throws ModelMapperException
     */
    public function mapFromArray(array $data)
    {
        $json = json_encode($data);
        if ($json === false) {
            throw new \InvalidArgumentException('Invalid array supplied.');
        }
        $object = json_decode($json);
        if ($object === null) {
            throw new \InvalidArgumentException('Invalid array supplied.');
        }

        return $this->mapFromObject($object);
    }

    /**
     * @param string $data
     *
     * @throws \InvalidArgumentException
     * @throws ModelMapperException
     */
    public function mapFromJson($data)
    {
        $object = json_decode($data);
        if ($object === null) {
            throw new \InvalidArgumentException('Invalid json supplied.');
        }

        return $this->mapFromObject($object);
    }

    /**
     * @param $object
     *
     * @return object
     */
    public function mapFromObject($object)
    {
        $mapper = new ModelMapper();

        return $mapper->map($object, $this);
    }

    /**
     * @param $xml
     *
     * @return object
     * @throws ModelMapperException
     */
    public function mapFromXml($xml)
    {
        $mapper = new XmlModelMapper();

        return $mapper->map($xml, $this);
    }
}
