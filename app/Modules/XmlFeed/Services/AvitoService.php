<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\Services;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\XmlFeed\Traits\ProductTrait;
use App\Modules\XmlFeed\UseCases\GetProductCategories;
use App\Packages\DataObjects\Catalog\Category\CategoryListItemData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\ProductFeature\ProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use DOMDocument;
use DOMImplementation;
use App\Modules\XmlFeed\Contracts\XmlFeedContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class AvitoService implements XmlFeedContract
{
    use ProductTrait;

    /** @var Collection<CategoryListItemData> */
    private Collection $productCategories;

    public function getDocument(Collection $products, ?callable $onEach = null): DOMDocument
    {
        $this->productCategories = App::call(GetProductCategories::class);

        $implementation = new DOMImplementation();

        $xml = $implementation->createDocument('', '');

        $xml->encoding = 'utf-8';
        $xml->formatOutput = true;

        $offers = $xml->createElement("Ads");

        $offers->setAttribute("formatVersion", "3");
        $offers->setAttribute("target", "Avito.ru");

        if ($products->isEmpty()) {
            $xml->appendChild($offers);
            return $xml;
        }

        /** @var \App\Packages\DataObjects\Catalog\Product\ProductData $product */
        foreach ($products as $product) {
            if (!$this->isAddToFeed($product)) {
                continue;
            }

            $offer = $xml->createElement("Ad");

            $Id = $xml->createElement("Id", (string)$product->id);
            $offer->appendChild($Id);

            $ContactPhone = $xml->createElement("ContactPhone", "88005114801");
            $offer->appendChild($ContactPhone);

            $Address = $xml->createElement("Address", "Россия, Москва, Веткина 4");
            $offer->appendChild($Address);

            $Title = $xml->createElement("Title", $product->name . ', Ювелирочка');
            $offer->appendChild($Title);

            $Description = $xml->createElement(
                "Description",
                $this->getDescription($product->description, $product->sku)
            );
            $offer->appendChild($Description);

            $ContactMethod = $xml->createElement("ContactMethod", "По телефону и в сообщениях");
            $offer->appendChild($ContactMethod);

            $Brand = $xml->createElement("Brand", "Ювелирочка");
            $offer->appendChild($Brand);

            $GoodsType = $xml->createElement("GoodsType", "Ювелирные изделия");
            $offer->appendChild($GoodsType);

            $Category = $xml->createElement("Category", "Часы и украшения");
            $offer->appendChild($Category);

            $AdType = $xml->createElement("AdType", "Товар от производителя");
            $offer->appendChild($AdType);

            $Condition = $xml->createElement("Condition", "Новое");
            $offer->appendChild($Condition);

            $GoodsSubType_info = $this->getAvitoType($product);
            $GoodsSubType = $xml->createElement("GoodsSubType", $GoodsSubType_info);
            $offer->appendChild($GoodsSubType);

            $productMaterial = $this->getProductFeatures($product, FeatureTypeEnum::METAL)->first();
            if ($productMaterial instanceof ProductFeatureData) {
                $Material_info = $this->getAvitoMaterial($productMaterial->feature->value);
                $Material = $xml->createElement("Material", $Material_info);
                $offer->appendChild($Material);
            }

            $priceAmount = $this->getProductPriceAmount($product);
            if ($priceAmount > 0) {
                $Price = $xml->createElement("Price", (string)$priceAmount);
                $offer->appendChild($Price);
            }

            $sizeTypes = ['Кольца и перстни'];
            if (in_array($GoodsSubType_info, $sizeTypes)) {
                $sizes = $this->getAvitoRingsSizes($product);
                if (!empty($sizes)) {
                    $SizesValues = $xml->createElement("SizeValues");
                    foreach ($sizes as $size) {
                        $OneSizeValue = $xml->createElement("Size", $size);
                        $SizesValues->appendChild($OneSizeValue);
                    }
                    $offer->appendChild($SizesValues);
                }
            }

            $productProba = $this->getProductFeatures($product, FeatureTypeEnum::PROBE)->first();
            if (!empty($productProba) && !empty($productMaterial)) {
                $Proba_info = $this->getAvitoProba(
                    $productMaterial->feature->value,
                    (int)$productProba->feature->value
                );
                $Proba = $xml->createElement("Proba", $Proba_info);
                $offer->appendChild($Proba);
            }

            $productInserts = $this->getProductFeatures($product, FeatureTypeEnum::INSERT);
            $Vstavka_info = $this->getAvitoVstavka($productInserts);
            $Vstavka = $xml->createElement("InsertStone", $Vstavka_info);
            $offer->appendChild($Vstavka);

            $productScalingImages = [];
            $productImages = $this->getProductImages($product);
            /** @var PreviewImageData $item */
            foreach ($productImages as $item) {
                $productScalingImages[] = $this->avitoScalingImage($item->image_url_lg);
            }

            $Images = $xml->createElement("Images");
            foreach ($productScalingImages as $item) {
                $OneImg = $xml->createElement("Image");
                $OneImg->setAttribute("url", $item);
                $Images->appendChild($OneImg);
            }
            $offer->appendChild($Images);

            $offers->appendChild($offer);

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }

        $xml->appendChild($offers);

        return $xml;
    }

    private function isAddToFeed(ProductData $product): bool
    {
        $productImages = $this->getProductImages($product);
        if ($productImages->isEmpty()) {
            return false;
        }

        return true;
    }

    private function getProductImages(ProductData $product): Collection
    {
        $images = [];
        $productImages = array_merge([$product->preview_image], $product->images->all());
        $default_url = config("media-library.sizes.App\Modules\Catalog\Models\Product.default");
        foreach ($productImages as $productImage) {
            /** Исключение технических изображений */
            if (
                str_contains($productImage->image_url_lg, 'Technical')
                || str_contains($productImage->image_url_lg, $default_url)
            ) {
                continue;
            }
            $images[] = $productImage;
        }

        return new Collection($images);
    }

    /** Сопоставление типов изделий юви и авито */
    private function getAvitoType(ProductData $product): string
    {
        /** @var Collection<CategoryListItemData> $parentCategories */
        $parentCategories = $this->productCategories->whereNull('parent_id');

        $productCategory = null;
        foreach ($product->categories as $categoryId) {
            /** @var CategoryListItemData|null $productCategory */
            $productCategory = $parentCategories->where('id', '=', $categoryId)->first();
            if ($productCategory instanceof CategoryListItemData) {
                break;
            }
        }

        return match ($productCategory?->title) {
            'Подвески', 'Подвеска' => 'Кулоны и подвески',
            'Кольцо', 'Кольца' => 'Кольца и перстни',
            'Серьги', 'Серьга' => 'Серьги',
            'Цепи', 'Цепь' => 'Цепи',
            'Браслеты', 'Браслет' => 'Браслеты',
            'Броши', 'Брошь' => 'Броши',
            'Колье' => 'Колье',
            'Комплекты', 'Комплект' => 'Комплекты',
            'Пирсинг' => 'Пирсинг',
            default => 'Другое',
        };
    }

    /** Сопоставление вставок изделий юви и авито */
    private function getAvitoVstavka(Collection $inserts): string
    {
        $returned = [];

        /** @var ProductFeatureData $insert */
        foreach ($inserts as $insert) {
            $returned[] = match ($insert->feature->value) {
                'Без вставки' => 'NO_VSTAVKA',
                'Бриллиант' => 'Бриллиант',
                'Фианит' => 'Фианит',
                'Сапфир' => 'Сапфир',
                'Изумруд' => 'Изумруд',
                'Жемчуг' => 'Жемчуг',
                'Топаз' => 'Топаз',
                'Аметист' => 'Аметист',
                'Янтарь' => 'Янтарь',
                'Рубин' => 'Рубин',
                'Александрит' => 'Александрит',
                'Гранат' => 'Гранат',
                default => 'Другое',
            };
        }

        while (($index = array_search('NO_VSTAVKA', $returned)) !== false) {
            unset($returned[$index]);
        }

        $unique_array = array_unique($returned);
        if (empty($unique_array)) {
            return 'Без вставок';
        }

        return implode('|', $unique_array);
    }

    /** Сопоставление материалов изделий юви и авито */
    private function getAvitoMaterial(string $material): string
    {
        return match ($material) {
            'Золото' => 'Золото',
            'Серебро' => 'Серебро',
            'Сталь' => 'Сталь',
            'Платина' => 'Платина',
            default => 'Другое',
        };
    }

    /** Возвращает массив размеров по кольцу или false, если ничего не найдено */
    private function getAvitoRingsSizes(ProductData $product): array
    {
        $sizes = [];
        /** @var ProductOfferData $offer */
        foreach ($product->trade_offers as $offer) {
            if (!empty($offer->size)) {
                $sizes[] = str_replace('.', ',', $offer->size);
            }
        }

        return $sizes;
    }

    /** Сопоставление проб изделий юви и авито */
    private function getAvitoProba(string $material, int $proba): string
    {
        $zolotoArray = [583, 585, 750];
        $serebroArray = [925, 875];
        $platinaArray = [900, 950];

        $default = 'Другая';
        $returnedProba = match ($material) {
            'Золото' => in_array($proba, $zolotoArray) ? $proba : $default,
            'Серебро' => in_array($proba, $serebroArray) ? $proba : $default,
            'Платина' => in_array($proba, $platinaArray) ? $proba : $default,
            default => $default
        };

        return (string)$returnedProba;
    }

    private function getDescription(string $productDesc, string $productSku): string
    {
        return "<p>✅ Ювелирочка [официальный аккаунт]</p>
            <p>{$productDesc}</p>
            <p>Артикул: {$productSku}</p>
            <p>«Ювелирочка» – российский бренд ювелирных украшений для ценителей природных камней,
                высокого ювелирного искусства и элегантного дизайна.
                Мы предлагаем эксклюзивные линейки золотых и серебряных украшений
                от ведущих российских производителей.</p>
            <p style='font-size: 18px; font-weight: 600; margin-bottom: 1rem;'>🔹Как оформить заказ?</p>
            <ol>
                <li style='margin-left: 1rem;'>Позвоните оператору, назовите номер артикула украшения на странице Авито
                и необходимый размер (колец, цепей, браслетов).</li>
                <li style='margin-left: 1rem;'>Оператор проконсультирует по выбранному изделию, расскажет
                про возможные способы оплаты (доступны: предоплата, оплата по факту получения и рассрочка)
                и предложит удобные способы доставки.</li>
            </ol>
            <p>Вы получите заказ в красивой фирменной упаковке с сертификатом компании.</p>
            <p style='font-size: 18px; font-weight: 600; margin-bottom: 1rem;'>🔹Гарантируем качество! Все изделия:</p>
            <ul>
                <li style='margin-left: 1rem;'>Проходят проверки государственной инспекции пробирного надзора РФ и
                системы внутреннего контроля качества компании.</li>
                <li style='margin-left: 1rem;'>Имеют нить с пломбой и биркой, на которой указана подробная информация
                о металле и вставке.</li>
                <li style='margin-left: 1rem;'>Соответствуют стандартам качества РФ: золотые изделия 375,
                585 или 750 пробы и серебряные изделия 925 пробы. На золотых украшениях стоит печать производителя
                и проба Российской государственной пробирной палаты.</li>
            </ul>
            <p style='font-size: 18px; font-weight: 600; margin-bottom: 1rem;'>🔹Возможные варианты доставки</p>
            <p style='font-size: 18px; font-weight: 600'>Курьерская служба:</p>
            <ol>
                <li style='margin-left: 1rem;'>Доставка курьерскими службами осуществляется
                Курьер Сервис Экспресс (КСЕ), Dalli, TopDelivery, СДЭК, Почта России.</li>
                <li style='margin-left: 1rem;'>Доставка доступна во всех крупных городах и районных центрах России.</li>
                <li style='margin-left: 1rem;'>В день доставки с вами свяжется курьер заранее до прибытия на адрес.</li>
                <li style='margin-left: 1rem;'>Для жителей Москвы доступна доставка собственной курьерской службой.</li>
            </ol>
            <p style='font-size: 18px; font-weight: 600'>Пункты выдачи партнеров:</p>
            <ol>
                <li style='margin-left: 1rem;'>Доставка осуществляется партнерскими службами:
                Почта России, 5Post, Boxberry.</li>
                <li style='margin-left: 1rem;'>Доступна во всех центральных и отдаленных районов России.</li>
                <li style='margin-left: 1rem;'>Список ПВЗ представлен на карте при оформлении заказа.</li>
                <li style='margin-left: 1rem;'>Забрать заказ можно в удобное для вас время.</li>
            </ol>
            <p style='font-size: 18px; font-weight: 600'>ПВЗ:</p>
            <ol>
                <li style='margin-left: 1rem;'>ПВЗ находится по адресу: Москва, Веткина 2а,
                стр 2 – 8 минут пешком от станции Марьина Роща и 1 минута от МЦД .</li>
                <li style='margin-left: 1rem;'>График работы: 10-00 - 20-00.</li>
                <li style='margin-left: 1rem;'>Способ оплаты: наличные и карта.</li>
                <li style='margin-left: 1rem;'>Доставка бесплатная.</li>
                <li style='margin-left: 1rem;'>Возможность примерки и частичного выкупа заказа.</li>
            </ol>
            <p style='font-size: 18px; font-weight: 600; margin-bottom: 1rem'>ОБРАТИТЕ ВНИМАНИЕ!</p>
            <p>Цены на изделия могут меняться в связи с ежедневно обновляемым ассортиментом.
            Указанная цена актуальна только в день заказа.</p>";
    }

    private function avitoScalingImage(string $url): string
    {
        $url = str_replace(' ', '%20', $url);
        return $url . "?width=1152&height=864";
    }

    private function getProductPriceAmount(ProductData $product): int
    {
        $prices = $this->getProductPriceAmounts($product);
        return $prices[OfferPriceTypeEnum::REGULAR->value] ?? 0;
    }

    private function getProductFeatures(ProductData $product, FeatureTypeEnum $featureType): Collection
    {
        $productFeatureData = $product->product_features->where('feature.type', '=', $featureType)->all();
        return new Collection($productFeatureData);
    }
}
