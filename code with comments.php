// Product Specials - стандартный запрос акционных цен OpenCart
$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

// ========== ПРАВИЛА СКИДОК ПО КОЛИЧЕСТВУ ==========
// Массив правил: ID товара => минимальное количество для скидки
$discount_rules = array(
    171 => 30,  // Товар 171 - скидка от 30 шт
    174 => 30,  // Товар 174 - скидка от 30 шт  
    15 => 30,   // Товар 15 - скидка от 30 шт
    113 => 10,  // Товар 113 - скидка от 10 шт
    133 => 10   // Товар 133 - скидка от 10 шт
);

// Получаем количество для КОНКРЕТНОЙ комбинации товара+опция
$current_qty = 0;

// Формируем JSON строку опций для поиска в базе
$option_json = $this->db->escape(json_encode($cart['option']));

// Ищем в корзине товар с ТАКИМИ ЖЕ опциями
$cart_item_query = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "cart WHERE product_id = '" . (int)$cart['product_id'] . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND `option` = '" . $option_json . "'");

// Если нашли - берем количество этой конкретной позиции
if ($cart_item_query->num_rows) {
    $current_qty = $cart_item_query->row['quantity'];
}

// ПРОВЕРКА ПРАВИЛ ДЛЯ КОНКРЕТНОЙ ПОЗИЦИИ
if (isset($discount_rules[$cart['product_id']]) && $current_qty < $discount_rules[$cart['product_id']]) {
    // УСЛОВИЕ: Товар есть в правилах И количество МЕНЬШЕ минимального
    // ДЕЙСТВИЕ: НЕ применяем скидку, оставляем обычную цену
    // Переменная $price уже содержит обычную цену из $product_query->row['price']
    
} elseif ($product_special_query->num_rows) {
    // УСЛОВИЕ: Либо товара нет в правилах, либо количество ДОСТАТОЧНОЕ
    // ДЕЙСТВИЕ: Применяем акционную цену
    $price = $product_special_query->row['price'];
}
// ========== КОНЕЦ ПРАВИЛ ДЛЯ СКИДОК ==========