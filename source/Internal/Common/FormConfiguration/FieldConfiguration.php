<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Common\FormConfiguration;

/**
 * Class FieldConfiguration
 */
class FieldConfiguration implements FieldConfigurationInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var bool
     */
    private $isRequired;

    /**
     * @var bool
     */
    private $isAlwaysRequired;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return FieldConfiguration
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return FieldConfiguration
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     * @return FieldConfiguration
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAlwaysRequired()
    {
        return $this->isAlwaysRequired;
    }

    /**
     * @param bool $isAlwaysRequired
     * @return FieldConfiguration
     */
    public function setIsAlwaysRequired($isAlwaysRequired)
    {
        $this->isAlwaysRequired = $isAlwaysRequired;
        return $this;
    }
}
