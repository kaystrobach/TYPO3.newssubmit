<?php
namespace T3ext\Newssubmit\ViewHelpers\Form;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Class SelectViewHelper
 *
 * Small improvement of the core version to support single select of a
 * multi value like categories
 */
class SelectViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\SelectViewHelper
{

    /**
     * Render the tag.
     *
     * @return string rendered tag.
     * @api
     */
    public function render()
    {
        $name = $this->getName();
        if ($this->arguments['multiple']) {
            if ((int)$this->arguments['size'] !== 1) {
                $this->tag->addAttribute('multiple', 'multiple');
            }
            $name .= '[]';
        }
        $this->tag->addAttribute('name', $name);
        $options = $this->getOptions();
        if (empty($options)) {
            $options = array('' => '');
        }
        $this->tag->setContent($this->renderOptionTags($options));
        $this->addAdditionalIdentityPropertiesIfNeeded();
        $this->setErrorClassAttribute();
        $content = '';
        // register field name for token generation.
        // in case it is a multi-select, we need to register the field name
        // as often as there are elements in the box
        if ($this->arguments['multiple'] && (int)$this->arguments['size'] !== 1) {
            $content .= $this->renderHiddenFieldForEmptyValue();
            for ($i = 0; $i < count($options); $i++) {
                $this->registerFieldNameForFormTokenGeneration($name);
            }
        } else {
            $this->registerFieldNameForFormTokenGeneration($name);
        }
        $content .= $this->tag->render();
        return $content;
    }
}