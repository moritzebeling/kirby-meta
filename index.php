<?php

Kirby::plugin('moritzebeling/meta', [

	'options' => [
		'fieldnames' => [
			'description' => 'description',
			'tags' => 'tags',
			'image' => false,
		]
	],

	'pageMethods' => [
		'metaDescription' => function (): string {

			$fieldname = option('moritzebeling.meta.fieldnames.description');

			if( $this->{$fieldname}()->isNotEmpty() ){
				return $this->{$fieldname}()->value();
			}
			return $this->site()->{$fieldname}()->value();

		},
		'metaKeywords' => function (): array {

			$fieldname = option('moritzebeling.meta.fieldnames.tags');

			$tags = array_unique( array_merge(
				$this->{$fieldname}()->split(),
				$this->site()->{$fieldname}()->split()
			));
			return array_slice( $tags, 0, 12 );

		},
		'ogImage' => function ( ?string $filenam = null ) {

			$fieldname = option('moritzebeling.meta.fieldnames.image');

			if( $this->hasImages() ){
				if( $this->fieldname === false ){
					return $this->image();
				} else {
					return $this->{$fieldname}->toFile();
				}
			}
			return $this->site()->image();

		},
	],

]);
