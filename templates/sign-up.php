<main>
        <form class="form container <?= count($errors) ? 'form--invalid' : ''?>" action = "sign-up.php" method="post" autocomplete="off"> <!-- form--invalid -->
            <h2>Регистрация нового аккаунта</h2>
            <div class="form__item" <?= isset($errors['email']) ? 'form__item--invalid' : '';?>"> <!-- form__item--invalid -->
                <label for="email">E-mail <sup>*</sup></label>
                <input id="email" type="text" name="email" value="<?= $user['email'] ?? '';?>" placeholder="Введите e-mail">
                <span class="form__error"> <?= isset($errors['email']) ? $errors['email'] : 'Введите почтовый адрес';?></span>
            </div>
            <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '';?>">
                <label for="password">Пароль <sup>*</sup></label>
                <input id="password" type="password" name="password" value="<?= $user['password'] ?? '';?>" placeholder="Введите пароль">
                <span class="form__error"> <?= isset($errors['password']) ? $errors['password'] : 'Введите пароль';?></span>
            </div>
            <div class="form__item" <?= isset($errors['name']) ? 'form__item--invalid' : '';?>>
                <label for="name">Имя <sup>*</sup></label>
                <input id="name" type="text" name="name" value="<?= $user['name'] ?? '';?>" placeholder="Введите имя">
                <span class="form__error"> <?= isset($errors['name']) ? $errors['name'] : 'Введите имя';?></span>
            </div>
            <div class="form__item" <?= isset($errors['message']) ? 'form__item--invalid' : '';?>>
                <label for="message">Контактные данные <sup>*</sup></label>
                <textarea id="message" name="message" value="<?= $user['message'] ?? '';?>" placeholder="Напишите как с вами связаться"></textarea>
                <span class="form__error"> <?= isset($errors['message']) ? $errors['message'] : 'Укажите контакт';?></span>
            </div>
            <?php if (isset($errors)): ?>
                <span class="form__error form__error--bottom"> Пожалуйста, исправьте ошибки в форме .</span>
            <?php endif; ?>
            <button type="submit" class="button">Зарегистрироваться</button>
            <a class="text-link" href="#">Уже есть аккаунт</a>
        </form>
</main>

