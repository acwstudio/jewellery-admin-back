<?php

namespace Database\Seeders;

use App\Modules\Stores\Models\Subway;
use Illuminate\Database\Seeder;

class StoreSubwaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jayParsedArr = [
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Улица Подбельского"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Черкизовская"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Преображенская площадь"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Сокольники"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Красносельская"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Комсомольская"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Красные ворота"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Чистые пруды"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Лубянка"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Охотный ряд"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Библиотека им. Ленина"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Кропоткинская"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Парк культуры"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Фрунзенская"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Спортивная"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Воробьёвы горы"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Университет"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Проспект Вернадского"
            ],
            [
                "line" => "Сокольническая",
                "color" => "E42313",
                "station" => "Юго-Западная"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Красногвардейская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Домодедовская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Орехово"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Царицыно"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Кантемировская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Каширская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Коломенская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Автозаводская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Павелецкая"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Новокузнецкая"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Театральная"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Тверская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Маяковская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Белорусская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Динамо"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Аэропорт"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Сокол"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Войковская"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Водный стадион"
            ],
            [
                "line" => "Замоскворецкая",
                "color" => "4FB04F",
                "station" => "Речной вокзал"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Митино"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Волоколамская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Мякинино"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Строгино"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Крылатское"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Молодежная"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Кунцевская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Славянский бульвар"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Парк Победы"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Киевская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Смоленская [глуб.]"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Арбатская [глуб.]"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Площадь Революции"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Курская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Бауманская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Электрозаводская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Семеновская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Партизанская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Измайловская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Первомайская"
            ],
            [
                "line" => "Арбатско-Покровская",
                "color" => "0072BA",
                "station" => "Щелковская"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Александровский сад"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Арбатская [мелк.]"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Смоленская [мелк.]"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Киевская [мелк.]"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Выставочная"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Международная"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Студенческая"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Кутузовская"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Фили"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Багратионовская"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Филевский парк"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Пионерская"
            ],
            [
                "line" => "Филевская",
                "color" => "1EBCEF",
                "station" => "Кунцевская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Белорусская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Новослободская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Проспект Мира"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Комсомольская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Курская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Таганская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Павелецкая"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Добрынинская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Октябрьская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Парк культуры"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Киевская"
            ],
            [
                "line" => "Кольцевая",
                "color" => "915133",
                "station" => "Краснопресненская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Медведково"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Бабушкинская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Свиблово"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Ботанический сад"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "ВДНХ"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Алексеевская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Рижская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Проспект Мира"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Сухаревская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Тургеневская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Китай-город"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Третьяковская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Октябрьская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Шаболовская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Ленинский проспект"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Академическая"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Профсоюзная"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Новые Черемушки"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Калужская"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Беляево"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Коньково"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Теплый Стан"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Ясенево"
            ],
            [
                "line" => "Калужско-Рижская",
                "color" => "F07E24",
                "station" => "Новоясеневская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Выхино"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Рязанский проспект"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Кузьминки"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Текстильщики"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Волгоградский проспект"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Пролетарская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Таганская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Китай-город"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Кузнецкий мост"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Пушкинская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Баррикадная"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Улица 1905 года"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Беговая"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Полежаевская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Октябрьское поле"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Щукинская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Тушинская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Сходненская"
            ],
            [
                "line" => "Таганско-Краснопресненская",
                "color" => "943E90",
                "station" => "Планерная"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Новокосино"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Новогиреево"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Перово"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Шоссе Энтузиастов"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Авиамоторная"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Площадь Ильича"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Марксистская"
            ],
            [
                "line" => "Калининская",
                "color" => "FFCD1C",
                "station" => "Третьяковская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Алтуфьево"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Бибирево"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Отрадное"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Владыкино"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Петровско-Разумовская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Тимирязевская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Дмитровская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Савеловская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Менделеевская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Цветной бульвар"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Чеховская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Боровицкая"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Полянка"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Серпуховская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Тульская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Нагатинская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Нагорная"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Нахимовский проспект"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Севастопольская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Чертановская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Южная"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Пражская"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Улица Академика Янгеля"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Аннино"
            ],
            [
                "line" => "Серпуховско-Тимирязевская",
                "color" => "ADACAC",
                "station" => "Бульвар Дмитрия Донского"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Марьина Роща"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Достоевская"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Трубная"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Сретенский бульвар"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Чкаловская"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Римская"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Крестьянская застава"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Дубровка"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Кожуховская"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Печатники"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Волжская"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Люблино"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Братиславская"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Марьино"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Борисово"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Шипиловская"
            ],
            [
                "line" => "Люблинско-Дмитровская",
                "color" => "BED12C",
                "station" => "Зябликово"
            ],
            [
                "line" => "Каховская",
                "color" => "88CDCF",
                "station" => "Каширская"
            ],
            [
                "line" => "Каховская",
                "color" => "88CDCF",
                "station" => "Варшавская"
            ],
            [
                "line" => "Каховская",
                "color" => "88CDCF",
                "station" => "Каховская"
            ],
            [
                "line" => "Бутовская",
                "color" => "BAC8E8",
                "station" => "Битцевский парк"
            ],
            [
                "line" => "Бутовская",
                "color" => "BAC8E8",
                "station" => "Лесопарковая"
            ],
            [
                "line" => "Бутовская",
                "color" => "BAC8E8",
                "station" => "Улица Старокачаловская"
            ],
            [
                "line" => "Бутовская",
                "color" => "BAC8E8",
                "station" => "Улица Скобелевская"
            ],
            [
                "line" => "Бутовская",
                "color" => "BAC8E8",
                "station" => "Бульвар адмирала Ушакова"
            ],
            [
                "line" => "Бутовская",
                "color" => "BAC8E8",
                "station" => "Улица Горчакова"
            ],
            [
                "line" => "Бутовская",
                "color" => "BAC8E8",
                "station" => "Бунинская аллея"
            ]
        ];

        foreach ($jayParsedArr as $subway) {
            Subway::query()->create($subway);
        }
    }
}
