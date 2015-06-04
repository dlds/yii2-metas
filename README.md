# Meta tags for Yii 2.x
Meta tags extension for Yii 2.0. Used for registering appropriate meta tags into
current web view.

## Available tags types
- [OpenGraph](http://ogp.me)

## Configuration
```
'components' => [
	'opengraph' => [
		'class' => 'dlds\metas\OpenGraph',
                'title' => 'Default title goes here',
                'description' => 'Default description goes here',
                //...
	],
	//...
],
```

## Usage
Meta tags must be registered before page render do its work

```
Yii::$app->opengraph->title = 'Custom title';
Yii::$app->opengraph->description = 'Custom description';
```

## Available Properties
- [OpenGraph Tags](http://ogp.me/#metadata)