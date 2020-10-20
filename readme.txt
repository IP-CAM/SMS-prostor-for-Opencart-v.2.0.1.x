Краткая инструкция по работе с модулем:

1. Перейдите в Установщик дополнений OCMOD
2. Нажмите кнопку Загрузить и выберите архив модуля. Модулб будет загружен и установлен.

Возможные ошибки:
- для успешной установки ocmod модуля необходим доступ по фтп (требование Opencart),
либо модуль https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=18892&filter_search=iSenseLabs,
который убирает требование включенного фтп.
- проверьте, установлено ли дополнение ZIP в настройках php

3. После успешной загрузки модуля необходимо интегрировать его файлы в Opencart. Для этого
перейдите в Менеджер дополнений и нажмите кнопку Обновить. Слева в основном меню появится кнопка модуля SMSPROSTOR.
4. Теперь нужно дать разрешения на работу модуля. Перейдите Пользователи->Группы пользователей и напротив группы
Administrator (в большинстве случаев) нажмите кнопку Редактировать. В списках Разрешить просмотр и Разрешить редактирование
найдите модуль tool/smsprostor (он почти в самом низу) и установите галочки. Сохраните настройки.
5. С помощью кнопки в главном меню Opencart перейдите к модулю SMSPROSTOR.
6. На вкладке Настройки шлюза установите логин, пароль (можно получить по ссылке https://prostor-sms.ru/?crmplatform=opencart),
а также введите телефон администратора в формате 79999999999. На телефон администратора будут отправляться те смс,
которые предназначены для владельца магазина. Сохраните настройки модуля.
7. Если введены корректные учетные данные, в верхней части модуля отобразится ваш текущий счет и на вкладке Настройки шлюза
станет доступным список для выбора имени отправителя. При необходимости выберите отправителя.
8. Вкладка Рассылка используется для отправки смс отдельному покупателю или сразу группе покупателей.
9. Вкладка Уведомления используется для настроек смс:
- Интеграция (выкл\вкл) - включает или отключает отправку всех смс
- Статус заказа покупателя - статус заказа, при переходе сделки в который покупатель будет получать смс
- Отправлять смс покупателю - включает и отключает отправку смс покупателю
- Смс покупателю - текст смс, можно воспользоватеься кнопками, которые вставляют шаблоны
- Статус заказа администратора - статус заказа, при переходе сделки в который администратор будет получать смс
- Отправлять смс администратору - включает и отключает отправку смс администратору
- Смс администратору - текст смс, можно воспользоватеься кнопками, которые вставляют шаблоны