// ========== ПРАВИЛА СКИДОК ПО КОЛИЧЕСТВУ ==========
$discount_rules = array(170 => 30, 171 => 30, 172 => 30, 173 => 30, 174 => 30);

// Считаем количество только для ЭТОЙ КОНКРЕТНОЙ комбинации товара+опция
$current_qty = $cart['quantity']; // Количество именно этой позиции

// Проверяем правило для конкретной позиции
if (isset($discount_rules[$cart['product_id']]) && $current_qty < $discount_rules[$cart['product_id']]) {
    // Не применяем скидку для этой позиции - оставляем обычную цену
    // $price уже содержит обычную цену из $product_query->row['price']
} elseif ($product_special_query->num_rows) {
    // Применяем скидку для этой позиции
    $price = $product_special_query->row['price'];
}
// ========== КОНЕЦ ПРАВИЛ СКИДОК ==========
