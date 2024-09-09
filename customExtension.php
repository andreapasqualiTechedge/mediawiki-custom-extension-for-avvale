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

    // Logic to determine if the page has been approved (checking FlaggedRevs status somehow?)
    private static function isPageApproved( $title ) {

        $pageId = $title->getArticleID();
        if ( !$pageId ) {
            return false;
        }
    
        // Access the database
        /* $dbr = wfGetDB( DB_REPLICA );
        $res = $dbr->select(
            'flaggedrevs',
            ['fr_page_id', 'fr_rev_id', 'fr_flags', 'fr_tags'],
            ['fr_page_id' => $pageId],
            __METHOD__,
            ['ORDER BY' => 'fr_rev_id DESC', 'LIMIT' => 1]
        );
        $row = $dbr->fetchObject( $res ); */

        // Access the database
        $dbr = $this->loadBalancer->getConnection( DB_REPLICA );
		$row = $dbr->newSelectQueryBuilder()
			->select(['fr_page_id', 'fr_rev_id', 'fr_flags', 'fr_tags'])
			->from('flaggedrevs')
			->where(['fr_page_id' => $pageId])
            ->orderBy('fr_rev_id DESC')
			->caller( __METHOD__ )
            ->fetchRow();
        if ( $row ) {
            // Check if the revision has flags indicating it is approved (stable version)
            if ( strpos( $row->fr_flags, 'stable' ) !== false ) {
                return true;
            }
        }
    
        return false;
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