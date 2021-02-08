<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once dirname(__DIR__).'/php-saml/_toolkit_loader.php';

class SamlSso_Metadata_Action extends Vtiger_Action_Controller {

	function loginRequired() {
		return false;
	}

	function checkPermission(Vtiger_Request $request) {
		return true;
	}

	function process(Vtiger_Request $request) {
		require_once dirname(__DIR__).'/php-saml/settings.php';

		try {
			// Now we only validate SP settings
			$settings = new OneLogin_Saml2_Settings($settingsInfo, true);

			$metadata = $settings->getSPMetadata();
			$errors = $settings->validateMetadata($metadata);

			if (empty($errors)) {
				header('Content-Type: text/xml');
				echo $metadata;
			} else {
				throw new OneLogin_Saml2_Error(
					'Invalid SP metadata: '.implode(', ', $errors),
					OneLogin_Saml2_Error::METADATA_SP_INVALID
				);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

}
