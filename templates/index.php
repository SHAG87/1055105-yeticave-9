<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--Выводим список категорий $categories-->
        <?php foreach ($categories as $category):?>
            <li class="promo__item promo__item--<?=$category['code']?>">
                <a class="promo__link" href="pages/all-lots.html"><?=esc_strong($category['name'])?></a>
            </li>
        <?php endforeach;?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($lots as $lot):?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=esc_strong($lot['img_url'])?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=esc($lot['category_name'])?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['id']?>" ><?=esc_strong($lot['name'])?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена <?=esc($lot['price'])?> </span>
                            <span class="lot__cost">Цена <?=format_sum(esc($lot['price']))?></span>
                        </div>
                        <div class="lot__timer timer <?=last_hour("tomorrow midnight") ? 'timer--finishing' : '' ?>">
                            <?=get_time_to_timer("tomorrow midnight") ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
</section>