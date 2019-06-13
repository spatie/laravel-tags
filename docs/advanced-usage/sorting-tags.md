---
title: Sorting tags
weight: 3
---

Whenever a tag is created its `order_column` will be set the highest value in that column + 1

Under the hood [spatie/eloquent-sortable](https://github.com/spatie/eloquent-sortable) is used, so you can use any model provided by that package. Here are some examples:

```php
//get all tags sorted on `order_column`
$orderedTags = Tags::ordered()->get(); 

//set a new order entirely
Tags::setNewOrder($arrayWithTagIds);

$myModel->moveOrderUp();
$myModel->moveOrderDown();

//let's grab a Tag instance
$tag = $orderedTags->first();

//move the tag to the first or last position
$tag->moveToStart();
$tag->moveToEnd();

$tag->swapOrder($anotherTag);
```

Of course you can also manually change the value of the `order_column`.

```php
$tag->order_column = 10;
$tag->save();
```
