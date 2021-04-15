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

	'siteMethods' => [
		'jsonLd' => function (): array {

			$json = [
				'@context' => 'https://schema.org/',
				'@type' => 'WebSite',
				'copyrightYear' => date('Y'),
				'name' => $this->title()->value(),
				'url' => $this->url()
			];

			if( $this->kirby()->languages()->count() > 1 ){
				$json['inLanguage'] = [];
				foreach( $this->kirby()->languages() as $lang ){
					$jsonld['inLanguage'] = [
						'@type' => 'Language',
						'name' => $lang->name(),
					];
				}
			}

			return $json;
		},
	],

	'pageMethods' => [
		'metaDescription' => function (): string {

			$fieldname = option('moritzebeling.meta.fieldnames.description');

			if( $this->{$fieldname}()->isNotEmpty() ){
				return $this->{$fieldname}()->value();
			}
			if( $this->site()->{$fieldname}()->isNotEmpty() ){
				return $this->site()->{$fieldname}()->value();
			}
			return $this->site()->title()->value();

		},
		'metaKeywords' => function (): array {

			$fieldname = option('moritzebeling.meta.fieldnames.tags');

			$tags = array_unique( array_merge(
				$this->{$fieldname}()->split(),
				$this->site()->{$fieldname}()->split()
			));
			return array_slice( $tags, 0, 12 );

		},
		'ogImage' => function ( ?string $fieldname = null ) {

			$fieldname = $fieldname ? $fieldname : option('moritzebeling.meta.fieldnames.image');

			if( $this->isHomePage() ){
				if( $image = $this->site()->ogimage()->toFile() ){
					return $image;
				}
			}

			if( $this->hasImages() ){
				if( $image = $this->content()->titleImage()->toFile() ){
					return $image;
				} else {
					return $this->image();
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

		},
		'title' => function (): Kirby\Cms\Field {

			if( $this->content()->title()->isNotEmpty() ){
				return $this->content()->title();
			}
			return new Field( $this, 'title', $this->filename() );

		},
		'caption' => function (): Kirby\Cms\Field {

			return $this->content()->caption();

		}
	]

]);
