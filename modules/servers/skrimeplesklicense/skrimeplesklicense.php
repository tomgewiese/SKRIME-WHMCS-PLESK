<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

include "skrime_plesk_license_apiclient.php";

function skrimeplesklicense_API($params, $url, $method, $apiParameter = []) {
    if (! in_array($method, ['GET', 'POST', 'PUT', 'DELETE']))
        return false;

    try {
        $method = strtolower($method);
        $api = new SkrimePleskLicenseApiClient($params['serverpassword']);
        return $api->{$method}($url, $apiParameter);
    } catch (Exception $exception) {
        logModuleCall(
            'skrimeplesklicense',
            __FUNCTION__,
            $params,
            $exception->getMessage(),
            $exception->getTraceAsString()
        );

        return false;
    }
}

function skrimeplesklicense_MetaData() {
    return array(
        'DisplayName' => 'SKRIMO Plesk Lizenzen',
        'APIVersion' => '1.2',
        'RequiresServer' => true,
    );
}

function skrimeplesklicense_ConfigOptions() {
    return array();
}

/**
 * Provision a new instance of a product/service.
 *
 * Attempt to provision a new instance of a given product/service. This is
 * called any time provisioning is requested inside of WHMCS. Depending upon the
 * configuration, this can be any of:
 * * When a new order is placed
 * * When an invoice for a new order is paid
 * * Upon manual request by an admin user
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function skrimeplesklicense_CreateAccount(array $params)
{
    try {
        $result = skrimeplesklicense_API($params, 'plesk/order', 'POST', [
            "duration" => "monthly",
            "tos" => true,
            "cancellation" => true,
        ]);

        if ($result['state'] == 'success') {
            $params['model']->serviceProperties->save(['skrime_plesk_id' => $result['data']['id']]);

            return 'success';
        } else {
            return $result['response'];
        }
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'skrimeplesklicense',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }
}

function skrimeplesklicense_Renew(array $params)
{
    $licenseId = $params['model']->serviceProperties->get('skrime_plesk_id');

    try {
        $result = skrimeplesklicense_API($params, 'plesk/renew', 'POST', [
            "productId" => $licenseId
        ]);

        if ($result['state'] == 'success') {
            $success = true;
            $errorMsg = '';
        } else {
            $success = false;
            $errorMsg = $result['response'];
        }

    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'skrimeplesklicense',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return [
        'success' => $success,
        'error' => $errorMsg,
    ];
}

/**
 * Terminate instance of a product/service.
 *
 * Called when a termination is requested. This can be invoked automatically for
 * overdue products if enabled, or requested manually by an admin user.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return string "success" or an error message
 */
function skrimeplesklicense_TerminateAccount(array $params)
{
    try {
        return 'success';
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'skrimeplesklicense',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }
}

/**
 * Test connection with the given server parameters.
 *
 * Allows an admin user to verify that an API connection can be
 * successfully made with the given configuration parameters for a
 * server.
 *
 * When defined in a module, a Test Connection button will appear
 * alongside the Server Type dropdown when adding or editing an
 * existing server.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function skrimeplesklicense_TestConnection(array $params)
{
    try {
        $result = skrimeplesklicense_API($params, 'accounting/balance', 'GET');

        if ($result['state'] == 'success') {
            $success = true;
            $errorMsg = '';
        } else {
            $success = false;
            $errorMsg = $result['response'];
        }
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'skrimeplesklicense',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        $success = false;
        $errorMsg = $e->getMessage();
    }

    return [
        'success' => $success,
        'error' => $errorMsg,
    ];
}

/**
 * Client area output logic handling.
 *
 * This function is used to define module specific client area output. It should
 * return an array consisting of a template file and optional additional
 * template variables to make available to that template.
 *
 * The template file you return can be one of two types:
 *
 * * tabOverviewModuleOutputTemplate - The output of the template provided here
 *   will be displayed as part of the default product/service client area
 *   product overview page.
 *
 * * tabOverviewReplacementTemplate - Alternatively using this option allows you
 *   to entirely take control of the product/service overview page within the
 *   client area.
 *
 * Whichever option you choose, extra template variables are defined in the same
 * way. This demonstrates the use of the full replacement.
 *
 * Please Note: Using tabOverviewReplacementTemplate means you should display
 * the standard information such as pricing and billing details in your custom
 * template or they will not be visible to the end user.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 *
 * @return array
 */
function skrimeplesklicense_ClientArea(array $params)
{
    // Determine the requested action and set service call parameters based on
    // the action.
    $requestedAction = isset($_REQUEST['license_action']) ? $_REQUEST['license_action'] : '';
    $licenseId = $params['model']->serviceProperties->get('skrime_plesk_id');

    try {
        switch ($requestedAction) {
            case 'binding':
                $templateFile = 'ip_binding.tpl';
                $ipAddress = '';

                $result = skrimeplesklicense_API($params, 'plesk/binding', 'GET', [
                    'productId' => $licenseId
                ]);

                if ($result['state'] == 'success') {
                    $ipAddress = $result['data']['ipAddress'];
                } else {
                    return array(
                        'tabOverviewModuleOutputTemplate' => 'error.tpl',
                        'templateVariables' => array(
                            'usefulErrorHelper' => $result['response'],
                        ),
                    );
                }

                return array(
                    'tabOverviewModuleOutputTemplate' => $templateFile,
                    'templateVariables' => compact('ipAddress'),
                );
                break;
            case 'change_binding':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['address']) || empty($_POST['address'])) {
                    try {
                        $serviceId = $params['serviceid'];
                        header("Location: clientarea.php?action=productdetails&id=" . $serviceId . "&license_action=binding");
                        exit;
                    } catch (Exception $exception) {
                        return array(
                            'tabOverviewModuleOutputTemplate' => 'error.tpl',
                            'templateVariables' => array(
                                'usefulErrorHelper' => 'Fehler beim Setzen des Bindings',
                            ),
                        );
                    }
                }

                $templateFile = 'ip_binding.tpl';
                $successMessage = '';
                $errorMessage = '';
                $ipAddress = '';

                $result = skrimeplesklicense_API($params, 'plesk/binding', 'POST', [
                    'productId' => $licenseId,
                    'ipAddress' => $_POST['address'],
                ]);

                if ($result['state'] == 'success') {
                    $successMessage = 'Die IP-Adresse wurde erfolgreich aktualisiert.';
                    $ipAddress = $result['data']['ipAddress'];
                } else {
                    return array(
                        'tabOverviewModuleOutputTemplate' => 'error.tpl',
                        'templateVariables' => array(
                            'usefulErrorHelper' => $result['response'],
                        ),
                    );
                }

                return array(
                    'tabOverviewModuleOutputTemplate' => $templateFile,
                    'templateVariables' => compact('ipAddress', 'successMessage', 'errorMessage'),
                );
                break;
            default:
                $templateFile = 'overview.tpl';
                $license = '';
                $serial = '';

                $result = skrimeplesklicense_API($params, 'plesk/single', 'GET', [
                    'productId' => $licenseId
                ]);

                if ($result['state'] == 'success') {
                    $license = isset($result['data']['productInfo']['licensekey']) ? $result['data']['productInfo']['licensekey'] : "IP Binding is Missing";
                } else {
                    return array(
                        'tabOverviewModuleOutputTemplate' => 'error.tpl',
                        'templateVariables' => array(
                            'usefulErrorHelper' => $result['response'],
                        ),
                    );
                }

                return array(
                    'tabOverviewModuleOutputTemplate' => $templateFile,
                    'templateVariables' => compact('license'),
                );
                break;
        }
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'skrimeplesklicense',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, display an error page.
        return array(
            'tabOverviewReplacementTemplate' => 'error.tpl',
            'templateVariables' => array(
                'usefulErrorHelper' => $e->getMessage(),
            ),
        );
    }
}

/**
 * Admin services tab additional fields.
 *
 * Define additional rows and fields to be displayed in the admin area service
 * information and management page within the clients profile.
 *
 * Supports an unlimited number of additional field labels and content of any
 * type to output.
 *
 * @param array $params common module parameters
 *
 * @see https://developers.whmcs.com/provisioning-modules/module-parameters/
 * @see provisioningmodule_AdminServicesTabFieldsSave()
 *
 * @return array
 */
function skrimeplesklicense_AdminServicesTabFields(array $params)
{
    try {
        $licenseId = $params['model']->serviceProperties->get('skrime_plesk_id');
        $returnData = [];
        $license = '';
        $serial = '';

        $result = skrimeplesklicense_API($params, 'plesk/single', 'GET', [
            'productId' => $licenseId
        ]);

        if ($result['state'] == 'success') {
            $license = isset($result['data']['productInfo']['licensekey']) ? $result['data']['productInfo']['licensekey'] : "IP Binding is Missing";
        }

        $returnData['Lizenz'] = $license;

        return $returnData;
    } catch (Exception $e) {
        logModuleCall(
            'skrimeplesklicense',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );
    }

    return array();
}
