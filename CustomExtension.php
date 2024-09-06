<?php


class CustomExtension {
    public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
        $title = $out->getTitle();

        // Check if the page is approved and needs further review
        if ( self::isPageApproved($title) ) {
            $out->addModules('ext.customExtension');

            $out->addHTML('<div class="custom-status">Status: Pending Review in Docware Drive</div>');
        }

        return true;
    }

    private static function isPageApproved( $title ) {
        // Logic to determine if the page has been approved (checking FlaggedRevs status somehow?)
        return true;
    }
}


class ApiDocwareFormSubmit extends ApiBase {

    public function execute() {
        $params = $this->extractRequestParams();

        # Just an example
        $documentName = $params['pageTitle'];

        $this->getResult()->addValue(null, $this->getModuleName(), [
            'status' => 'success',
            'message' => 'Form submitted successfully!'
        ]);
    }

    public function getAllowedParams() {
        return [
            'pageTitle' => [
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => true
            ]
        ];
    }
}

$wgAPIModules['docwareformsubmit'] = 'ApiDocwareFormSubmit';


?>