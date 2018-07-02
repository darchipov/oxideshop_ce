<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Acceptance\Frontend;


class ContactFormFrontendTest extends \OxidEsales\EshopCommunity\Tests\Acceptance\FlowThemeTestCase
{
    private $requiredClass = 'req';

    private $contactUrl = 'index.php?cl=contact';

    private $emailInputFieldXpathLocator = '//*[@id="contactEmail"]';

    private $emailLabelXpathLocator = '//label[@for="contactEmail"]';

    private $configuredRequiredInputFieldXpathLocator = '//*[@id="editval[oxuser__oxfname]"]';

    private $configuredRequiredFieldLabelXpathLocator = '//label[@for="editval[oxuser__oxfname]"]';

    /**
     * @group flow-theme
     */
    public function testContactFormRequiresEmailFieldToBeFilled()
    {
        $this->openContactForm();

        $emailInputField = $this->getElement($this->emailInputFieldXpathLocator);
        $this->assertTrue($emailInputField->hasAttribute('required'), 'The email field is always marked as required');

        $emailLabel = $this->getElement($this->emailLabelXpathLocator);
        $this->assertTrue($emailLabel->hasClass($this->requiredClass), 'The email field is always marked as required');

        $configuredInputField = $this->getElement($this->configuredRequiredInputFieldXpathLocator);
        $this->assertNull($configuredInputField->getAttribute('required'), 'The configured field is not marked as required without configuration');

        $configuredLabel = $this->getElement($this->configuredRequiredFieldLabelXpathLocator);
        $this->assertFalse(in_array($this->requiredClass, explode(' ', $configuredLabel->getAttribute('class'))), 'The configured field is not marked as required without configuration');
    }

    /**
     * @group flow-theme
     */
    public function testContactFormRequiresConfiguredFieldToBeFilled()
    {
        $this->openContactForm();

        $emailInputField = $this->getElement($this->emailInputFieldXpathLocator);
        $this->assertTrue($emailInputField->hasAttribute('required'),'The email field is always marked as required');

        $emailLabel = $this->getElement($this->emailLabelXpathLocator);
        $this->assertTrue($emailLabel->hasClass($this->requiredClass),'The email field is always marked as required');

        $configuredInputField = $this->getElement($this->configuredRequiredInputFieldXpathLocator);
        $this->assertTrue($configuredInputField->hasAttribute('required'), 'The configured field is marked as required after configuration');

        $configuredLabel = $this->getElement($this->configuredRequiredFieldLabelXpathLocator);
        $this->assertTrue($configuredLabel->hasClass($this->requiredClass), 'The configured field is marked as required after configuration');
    }

    private function openContactForm()
    {
        $this->openNewWindow($this->contactUrl);
    }
}
