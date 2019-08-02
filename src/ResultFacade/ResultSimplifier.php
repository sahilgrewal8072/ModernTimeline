<?php

declare( strict_types = 1 );

namespace ModernTimeline\ResultFacade;

use SMW\DIWikiPage;
use SMW\Query\PrintRequest;
use SMW\Query\QueryResult;
use SMW\Query\Result\ResultArray;

class ResultSimplifier {

	public function newSubjectCollection( QueryResult $result ): SubjectCollection {
		$subjects = [];

		foreach ( $result->getResults() as $diWikiPage ) {
			$subjects[] = $this->newSubject( $diWikiPage, $result->getPrintRequests(), $result );
		}

		return new SubjectCollection( $subjects );
	}

	/**
	 * @param DIWikiPage $resultPage
	 * @param PrintRequest[] $printRequests
	 * @param QueryResult $result
	 * @return Subject
	 */
	private function newSubject( DIWikiPage $resultPage, array $printRequests, QueryResult $result ): Subject {
		$propertyValueCollections = [];

		foreach ( $printRequests as $printRequest ) {
			$dataItems = ResultArray::factory( $resultPage, $printRequest, $result )->getContent();

			$propertyValueCollections[] = new PropertyValueCollection(
				$printRequest,
				$dataItems === false ? [] : $dataItems
			);
		}

		return new Subject( $resultPage, $propertyValueCollections );
	}

}