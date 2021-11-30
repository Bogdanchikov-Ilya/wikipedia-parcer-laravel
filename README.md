# Bacend часть. API на Laravel Парсер страниц Википедии с поиском статей и поиском по ключевому слову в найденных статьях 

####Использовал Laravel. Статья пришедшея с фронта разибивается по словам и добавляется в таблицу статей, затем вся статья разбивается по слову и добавляется в таблицу слов, в этой таблице слова со всех статей по отдельности. Реализован поиск слов в добавленных статьях со счетчиком введенного слова в каждой из статей (MySql с внешними ключами ). Подсчет слов проиходит во время добавления новой статьи и данные (id слова, id статьи в которой есть это слово, кол-во повторений) добавляются в связующую таблицу. 

### [Frontend часть](https://github.com/Bogdanchikov-Ilya/WikipediaParcer-Search-frontend)


