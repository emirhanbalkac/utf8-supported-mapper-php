<?php
/**
 * Created by PhpStorm.
 * User: milos.pejanovic
 * Date: 3/29/2016
 * Time: 9:27 AM
 */

namespace Mapper;
use Common\ModelReflection\Enum\TypeEnum;
use Common\ModelReflection\ModelClass;
use Common\ModelReflection\ModelPropertyType;
use Common\Util\Validation;
use Common\Util\Iteration;

class ModelMapper implements IModelMapper {

	/**
	 * @param object $source
	 * @param object $model
	 * @return object
	 */
	public function map($source, $model) {
		return $this->mapModel($source, $model);
	}

    /**
     * @param object $source
     * @param object $model
     * @return object
     * @throws \InvalidArgumentException
     */
	protected function mapModel($source, $model) {
        if(!is_object($source) || Validation::isEmpty($source)) {
            throw new \InvalidArgumentException('Source must be an object with properties.');
        }
        if(!is_object($model) || Validation::isEmpty($model)) {
            throw new \InvalidArgumentException('Model must be an object with properties.');
        }
        $modelClass = new ModelClass($model);

        foreach($modelClass->getProperties() as $property) {
            $sourceValue = Iteration::findValueByName($property->getName(), $source, $property->getPropertyValue());
            $mappedValue = $this->mapPropertyByType($property->getType(), $sourceValue);
            $property->setPropertyValue($mappedValue);
        }

        return $model;
    }

	/**
	 * @param ModelPropertyType $propertyType
	 * @param mixed $value
	 * @return mixed
	 */
	protected function mapPropertyByType(ModelPropertyType $propertyType, $value) {
        $mappedPropertyValue = $value;
		if($propertyType->isModel()) {
            $modelClassName = $propertyType->getModelClassName();
			if($propertyType->getActualType() === TypeEnum::ARR && (is_array($value) || is_object($value))) {
				if(is_object($value) && count($value) === 1) {
					$value = array($value);
				}
				$mappedPropertyValue = $this->mapModelArray($value, $modelClassName);
			}
			elseif($propertyType->getActualType() === TypeEnum::OBJECT && is_object($value)) {
			    $modelClassName = $propertyType->getModelClassName();
                $model = ModelClass::instantiate($modelClassName);
				$mappedPropertyValue = $this->mapModel($value, $model);
			}
		}

		return $mappedPropertyValue;
	}

	/**
	 * @param string $modelClassName
	 * @param array|object $source
	 * @return array
	 */
	protected function mapModelArray($source, $modelClassName) {
		$mappedModelArray = array();
		foreach($source as $value) {
			if(is_object($value)) {
                $model = ModelClass::instantiate($modelClassName);
				$mappedModelArray[] = $this->mapModel($value, $model);
			}
		}

		return $mappedModelArray;
	}

    /**
     * @param object $model
     * @return \stdClass
     */
	public function unmap($model) {
        return $this->unmapModel($model);
	}

    /**
     * @param object $model
     * @return \stdClass
     * * @throws \InvalidArgumentException
     */
	protected function unmapModel($model) {
        if(!is_object($model) || Validation::isEmpty($model)) {
            throw new \InvalidArgumentException('Model must be an object with properties.');
        }
        $modelClass = new ModelClass($model);
        $unmappedObject = new \stdClass();
        foreach($modelClass->getProperties() as $property) {
            $propertyKey = $property->getName();
            $propertyValue = $property->getPropertyValue();
            if(Validation::isEmpty($propertyValue)) {
                continue;
            }

            $unmappedObject->$propertyKey = $this->unmapValueByType($property->getType(), $propertyValue);
        }

        return $unmappedObject;
    }

	/**
	 * @param ModelPropertyType $propertyType
	 * @param mixed $value
	 * @return mixed
	 */
	protected function unmapValueByType(ModelPropertyType $propertyType, $value) {
		$unmappedPropertyValue = $value;

		if($propertyType->isModel()) {
			if($propertyType->getActualType() === TypeEnum::ARR && is_array($value)) {
				$unmappedPropertyValue = $this->unmapModelArray($value);
			}

			elseif($propertyType->getActualType() === TypeEnum::OBJECT && is_object($value)) {
				$unmappedPropertyValue = $this->unmapModel($value);
			}
		}

		return $unmappedPropertyValue;
	}

	/**
	 * @param array $modelArray
	 * @return array
	 */
	protected function unmapModelArray(array $modelArray) {
		$unmappedObjectArray = array();
		foreach($modelArray as $k => $v) {
			$unmappedObjectArray[$k] = $this->unmapModel($v);
		}

		return $unmappedObjectArray;
	}
}