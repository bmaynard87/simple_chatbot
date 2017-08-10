<?php

function get_reply($contents) {
	$entities = $contents->entities;

	//sort the entities by magnitude
	usort($entities, function($a, $b) {
		return strcmp($a->sentiment->magnitude, $b->sentiment->magnitude);
	});
	$entities = array_reverse($entities);

	if( ! $entities) {
		return '...';
	}
	
	$top_entity = $entities[0];
	
	return analyze_by_sentiment($top_entity);
}

function analyze_by_sentiment($entity) {
	$entity_name = $entity->name;
	$sentiment = get_sentiment($entity->sentiment->score);

	$str = '';

	if($entity->type == 'LOCATION') {
		switch($sentiment) {
			case 'clearly_negative':
				$str = "I'd stay away from $entity_name if I were you!";
				break;
			case 'negative':
				$str = "Hopefully things improve in $entity_name.";
				break;
			case 'neutral':
				$str = "I'd like to know more about $entity_name.";
				break;
			case 'positive':
				$str = "I'm glad you enjoy it in $entity_name!";
				break;
			case 'clearly_positive':
				$str = "$entity_name sounds like an amazing place!";
				break;
		}
	}
	if($entity->type == 'PERSON') {
		switch($sentiment) {
			case 'clearly_negative':
				$str = "Don't spend too much time around $entity_name!";
				break;
			case 'negative':
				$str = "Hopefully you can find someone you agree with.";
				break;
			case 'neutral':
				$str = "I'd like to know more about $entity_name.";
				break;
			case 'positive':
				$str = "I'm glad you enjoy $entity_name!";
				break;
			case 'clearly_positive':
				$str = "$entity_name sounds like an amazing person!";
				break;
		}
	}
	if($entity->type == 'CONSUMER_GOOD' || $entity->type == 'WORK_OF_ART') {
		switch($sentiment) {
			case 'clearly_negative':
				$str = "Throw it away!";
				break;
			case 'negative':
				$str = "Hopefully you find something you enjoy more than $entity_name.";
				break;
			case 'neutral':
				$str = "I'd like to know more about $entity_name.";
				break;
			case 'positive':
				$str = "I'm glad you enjoy $entity_name!";
				break;
			case 'clearly_positive':
				$str = "I also enjoy $entity_name!";
				break;
		}
	}
	if($entity->type == 'OTHER') {
		$str = '...';
	}

	return ucfirst($str);
}

function get_sentiment($score) {
	if($score > 1 || $score < -1) {
		return false;
	}

	switch($score) {
		case -1:
		case -0.9:
		case -0.8:
		case -0.7:
		case -0.6:
			return 'clearly_negative';
			break;
		case -0.5:
		case -0.4:
		case -0.3:
			return 'negative';
			break;
		case -0.2:
		case -0.1:
		case 0:
		case 0.1:
		case 0.2:
			return 'neutral';
			break;
		case 0.3:
		case 0.4:
		case 0.5:
			return 'positive';
			break;
		case 0.6:
		case 0.7:
		case 0.8:
		case 0.9:
		case 1:
			return 'clearly_positive';
			break;
	}
}