<form  <?php if (isset($item['id'])) { ?>
            action="/item/update/<?php echo $item['id'] ?>"
        <?php } else { ?>
            action="/item/add"
        <?php } ?>
      method="post" enctype="multipart/form-data">

    <?php if (isset($item['id'])): ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>">
    <?php endif; ?>
    <input type="text" name="value" value="<?php echo isset($item['item_name']) ? $item['item_name'] : '' ?>">
    <input type="file" name="item_img" value="">
    <?php if(!empty($item['item_img'])){echo '<img src="'.$item['item_img'].'"';}?>
    <br/>
    <input type="submit" value="提交">
</form>

<a class="big" href="/item/index">返回</a>