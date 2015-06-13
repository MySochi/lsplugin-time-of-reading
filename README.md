# LS Plugin: Time of reading (Время прочтения)

Плагин позволяет выводить время нужное для прочтения топика, типа: "7 минут для чтения".

## Настройки:
* `speed` — скорость прочтения (символов/мин.). **По умолчанию: 1200.**
* "Дельта" — это количество символов после которых время прочтения округляется до минуты.
  * `use_delta` — использовать дельту. **По умолчанию: true.**
  * `delta` — значение дельты. **По умолчанию: 200.**

## Использование
Плагин позволяет узнать количество времени для прочтения поста в секундах.
Для получения количества секунд используйте ``$oTopic->getTimeOfReading()``.
А для вывода в виде минут и секунд (со склонением) используйте Smarty плагин ``amount_minutes_seconds``, определённый в этом плагине.
#### Smarty plugin: amount_minutes_seconds. Параметры:
* `seconds` — количество секунд. **Объязательный.**
* `only_minute` — выводить только минуты. **По умолчанию: false.**
* `round_minute` — округлять к минуте. **По умолчанию: false.**

#### Пример:
Просто вставьте в нужное место шаблона (где определен объект топика) эту строчку:

``{amount_minutes_seconds seconds=$oTopic->getTimeOfReading() only_minute=false round_minute=false}``
