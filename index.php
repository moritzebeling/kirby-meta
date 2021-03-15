<?php

Kirby::plugin('moritzebeling/meta', [

	'options' => [
		'fieldnames' => [
			'description' => 'description',
			'tags' => 'tags',
			'image' => false,
		],
		'thumbs' => [
			'presets' => [
				'ogimage' => [ 'width' => 2000, 'height' => 2000, 'crop' => true ]
			]
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
				if( $fieldname === false ){
					return $this->image();
				} else {
					return $this->{$fieldname}->toFile();
				}
			}
			return $this->site()->image();

		},
	],

	'fileMethods' => [
		'alt' => function (): Kirby\Cms\Field {

			if( $this->content()->title()->isNotEmpty() ){
				return $this->content()->title();
			}
			return $this->parent()->title();
			return new Field( $this, 'title', $this->filename() );

		},
		'caption' => function (): Kirby\Cms\Field {

			return $this->content()->caption();

		}
	]

]);
