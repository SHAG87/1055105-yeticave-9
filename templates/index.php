<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--Выводим список категорий $categories-->
        <?php foreach ($categories as $category_name):?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?=esc_strong($category_name)?></a>
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
        <?php foreach ($announ as $key => $val):?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=esc_strong($val['url'])?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=esc($val['cat'])?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=esc_strong($val['name'])?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена <?=esc($val['price'])?> </span>
                            <span class="lot__cost">Цена <?=format_sum(esc($val['price']))?></span>
                        </div>
                        <?php if (last_hour()): ?>
                            <div class="lot__timer timer"><?=get_time_to_timer() ?></div>
                        <?php else: ?>
                            <div class=timer--finishing><?=get_time_to_timer() ?></div>")
                        <?php endif; ?>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
</section>